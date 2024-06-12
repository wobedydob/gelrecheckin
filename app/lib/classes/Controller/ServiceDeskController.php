<?php

namespace Controller;

use Entity\Collection;
use Model\Flight;
use Model\Luggage;
use Model\Passenger;
use Model\ServiceDesk;
use Service\Page;
use Service\Redirect;
use Service\Session;
use Service\View;

class ServiceDeskController
{
    private array $errors = [];

    public function login(): void
    {
        $serviceDesks = ServiceDesk::with(['balienummer'])->all();
        $this->authenticate();
        View::new()->render('views/templates/service-desk/service-desk-login.php', compact('serviceDesks'));
    }

    public function authenticate(): void
    {
        $post = $_POST;

        if (!isset($post['submit'])) {
            return;
        }

        $deskId = $post['desk_id'] ? \Util\StringHelper::sanitize($post['desk_id']) : false;
        $password = $post['password'] ?: false;

        $serviceDesk = ServiceDesk::with(['wachtwoord'])->where('balienummer', '=', $deskId)->first();
        $verify = password_verify($password, $serviceDesk->wachtwoord);
        $secondVerify = ServiceDesk::where('balienummer', '=', $deskId)->where('wachtwoord', '=', $password)->exists(); // only because the given database has not used any hashed passwords... stupid han sometimes...

        if (!$verify && !$secondVerify) {
            $this->errors['error'] = 'invalid credentials';
        }

        if (!$deskId) {
            $this->errors['desk_id'] = 'none given';
        }

        if (!$password) {
            $this->errors['password'] = 'none given';
        }

        if (!empty($this->errors)) {
            View::new()->render('views/templates/service-desk/service-desk-login.php', ['errors' => $this->errors]);
            return;
        }

        $user = ['id' => (int)$deskId, 'role' => 'service_desk'];

        Session::instance()->set('user', $user);
        Redirect::to('/dashboard');
    }

    public function flights(): void
    {
        $search = page()->get('search');
        $page = page()->get('page', 1);
        $orderBY = page()->get('sort', 'vluchtnummer');
        $orderDirection = page()->get('direction', 'DESC');

        $limit = page()->get('limit', 20);
        $offset = $limit * ($page - 1);

        if($search) {
            $flight = Flight::find($search);
            $flights = new Collection();

            if($flight) {
                $flights->addToCollection($flight);
            }

        } else {
            $flights = Flight::all($limit, $offset, $orderBY, $orderDirection);
        }

        View::new()->render('views/templates/service-desk/service-desk-flights.php', compact('flights', 'search', 'limit', 'orderBY', 'orderDirection'));
    }

    public function passengers(): void
    {
        $search = page()->get('search');
        $page = page()->get('page', 1);
        $orderBY = page()->get('sort', 'passagiernummer');
        $orderDirection = page()->get('direction', 'DESC');

        $limit = page()->get('limit', 20);
        $offset = $limit * ($page - 1);

        if($search) {
            $passenger = Passenger::find($search);
            $passengers = new Collection();

            if($passenger) {
                $passengers->addToCollection($passenger);
            }

        } else {
            $passengers = Passenger::all($limit, $offset, $orderBY, $orderDirection);
        }

        View::new()->render('views/templates/service-desk/service-desk-passengers.php', compact('passengers', 'search', 'limit', 'orderBY', 'orderDirection'));
    }

    public function luggages(): void
    {}

}
