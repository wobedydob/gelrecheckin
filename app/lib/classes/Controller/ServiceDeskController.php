<?php

namespace Controller;

use Entity\Collection;
use Model\Flight;
use Model\Luggage;
use Model\Passenger;
use Model\ServiceDesk;
use Service\Error;
use Service\Redirect;
use Service\Session;
use Service\View;

class ServiceDeskController
{
    private array $errors = [];

    public static function deskIds(): array|Collection
    {
        return ServiceDesk::with(['balienummer'])->all();
    }

    public function login(): void
    {
        $serviceDesks = self::deskIds();
        $this->authenticate();
        View::new()->render('views/templates/service-desk/service-desk-login.php', compact('serviceDesks'));
    }

    public function authenticate(): void
    {
        $post = $_POST;

        if (!isset($post['submit'])) {
            return;
        }

        $deskId = $post['desk_id'] ? \Util\Text::sanitize($post['desk_id']) : false;
        $password = $post['password'] ?: false;

        $serviceDesk = ServiceDesk::with(['wachtwoord'])->where('balienummer', '=', $deskId)->first();
        $verify = password_verify($password, $serviceDesk->wachtwoord);
        $secondVerify = ServiceDesk::where('balienummer', '=', $deskId)->where('wachtwoord', '=', $password)->exists(); // only because the given database has not used any hashed passwords... stupid han sometimes...

        if (!$verify && !$secondVerify) {
            $this->errors['credentials_error'] = 'Vekeerde gegevens opgegeven';
        }

        if (!empty($this->errors)) {
            $serviceDesks = ServiceDesk::with(['balienummer'])->all();
            Error::set($this->errors);
            View::new()->render('views/templates/service-desk/service-desk-login.php', ['serviceDesks' => $serviceDesks, 'errors' => $this->errors]);
            return;
        }

        $user = ['id' => (int)$deskId, 'role' => 'service_desk'];

        Session::instance()->set('user', $user);
        Redirect::to('/dashboard');
    }

    public function flights(): void
    {
        $user = auth()->user();
        $serviceDesk = ServiceDesk::with(['balienummer'])->where('balienummer', '=', $user->getId())->first();

        $search = page()->get('search');
        $page = page()->get('page', 1);
        $orderBY = page()->get('sort', 'vluchtnummer');
        $orderDirection = page()->get('direction', 'DESC');

        $limit = page()->get('limit', 20);
        $offset = $limit * ($page - 1);

        $flights = new Collection();

        if($search) {
            $flight = Flight::find($search);

            if($flight) {
                $flights->addToCollection($flight);
            }

        } else {

            if($user->getRole() === ServiceDesk::USER_ROLE) {
                $flights = $serviceDesk->getFlights($limit, $offset, $orderBY, $orderDirection);
            }

        }

        View::new()->render('views/templates/service-desk/service-desk-flights.php', compact('serviceDesk', 'flights', 'search', 'limit', 'orderBY', 'orderDirection'));
    }

    public function passengers(): void
    {
        $user = auth()->user();
        $serviceDesk = ServiceDesk::with(['balienummer'])->where('balienummer', '=', $user->getId())->first();

        $search = page()->get('search');
        $page = page()->get('page', 1);
        $orderBY = page()->get('sort', 'passagiernummer');
        $orderDirection = page()->get('direction', 'DESC');

        $limit = page()->get('limit', 20);
        $offset = $limit * ($page - 1);

        $passengers = new Collection();

        if($search) {
            $passenger = Passenger::find($search);


            if($passenger) {
                $passengers->addToCollection($passenger);
            }

        } else {
            $user = auth()->user();
            if($user->getRole() === ServiceDesk::USER_ROLE) {
                $passengers = $user->getModel()->getPassengers($limit, $offset, $orderBY, $orderDirection);
            }
        }

        View::new()->render('views/templates/service-desk/service-desk-passengers.php', compact('serviceDesk', 'passengers', 'search', 'limit', 'orderBY', 'orderDirection'));
    }

    public function luggages(): void
    {}

}
