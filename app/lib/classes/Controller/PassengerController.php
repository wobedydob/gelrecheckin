<?php

namespace Controller;

use Enums\CRUDAction;
use JetBrains\PhpStorm\NoReturn;
use Model\Flight;
use Model\Luggage;
use Model\Passenger;
use Model\ServiceDesk;
use Service\Auth;
use Service\Error;
use Service\Redirect;
use Service\Session;
use Service\View;
use Util\Text;

class PassengerController
{
    private array $errors = [];

    public function login(): void
    {
        $this->authenticate();
        View::new()->render('views/templates/passenger/passenger-login.php');
    }

    public function authenticate(): void
    {
        $post = $_POST;

        if (!isset($post['submit'])) {
            return;
        }

        $passengerId = $post['passenger_id'] ? Text::sanitize($post['passenger_id']) : false;
        $password = $post['password'] ?: false;

        $passenger = Passenger::with(['wachtwoord'])->where('passagiernummer', '=', $passengerId)->first();
        $verify = password_verify($password, $passenger?->wachtwoord ?? '');
        $secondVerify = Passenger::where('passagiernummer', '=', $passengerId)->where('wachtwoord', '=', $password)->exists(); // only because the given database has not used any hashed passwords... stupid han sometimes...

        if (!$verify && !$secondVerify) {
            $this->errors['credentials_error'] = 'Vekeerde gegevens opgegeven';
        }

        if (!empty($this->errors)) {
            Error::set($this->errors);
            View::new()->render('views/templates/passenger/passenger-login.php');
            return;
        }

        $user = ['id' => (int)$passengerId, 'role' => 'passenger'];
        Session::instance()->set('user', $user);
        Redirect::to('/dashboard');
    }

    public static function validate($id): void
    {
        if (auth()->withRole(Passenger::USER_ROLE) && auth()->user()->getId() != $id) {
            Redirect::to('/dashboard');
        }
    }

    public function dashboard(): void
    {
        $id = auth()->user()->getId();
        $passenger = Passenger::with(['passagiernummer', 'naam', 'geslacht'])->where('passagiernummer', '=', $id)->first();

        View::new()->render('views/templates/passenger/passenger-dashboard.php', compact('passenger'));
    }

    public function serviceDesk(): void
    {
        (new ServiceDeskController())->passengers();
    }

    public function show($id): void
    {
        if (auth()->withRole(Passenger::USER_ROLE)) {
            auth()->user()->getId() !== $id ?: Redirect::to('/dashboard');
        }

        $passenger = Passenger::find($id);

        if (!$passenger) {
            Redirect::to('/passagiers');
        }

        $luggages = Luggage::with(['objectvolgnummer', 'gewicht'])->where('passagiernummer', '=', $id)->all();

        View::new()->render('views/templates/passenger/passenger.php', compact('passenger', 'luggages'));
    }

    public function add(): void
    {
        $flights = null;

        $user = auth()->user();
        if ($user->getRole() === ServiceDesk::USER_ROLE) {
            $serviceDesk = ServiceDesk::with(['balienummer'])->where('balienummer', '=', $user->getId())->first();
            $flights = $serviceDesk->getFlights();
        }

        View::new()->render('views/templates/passenger/passenger-add.php', compact('flights'));
    }

    public function addPassenger(): void
    {
        $post = $this->handlePost(CRUDAction::ACTION_CREATE);
        $post['passenger_id'] = Passenger::nextPassengerId();

        $flights = $this->flights();

        if ($post) {
            $action = Passenger::create([
                'passagiernummer' => $post['passenger_id'],
                'naam' => $post['name'],
                'vluchtnummer' => $post['flight_id'],
                'geslacht' => $post['gender'],
                'balienummer' => $post['desk_id'],
                'stoel' => $post['seat'],
                'inchecktijdstip' => $post['checkin_time'],
                'wachtwoord' => $post['password'],
            ]);

            if ($action) {
                $this->errors['success'] = 'Passagier succesvol toegevoegd';
            } else {
                $this->errors['error'] = 'Passagier kon niet worden toegevoegd';
            }

            $passenger = new Passenger();
            $passenger->passagiernummer = null;
            $passenger->naam = $post['name'];
            $passenger->geslacht = $post['gender'];
            $passenger->balienummer = $post['desk_id'];
            $passenger->stoel = $post['seat'];
            $passenger->inchecktijdstip = $post['checkin_time'];
            $passenger->wachtwoord = $post['password'];

            View::new()->render('views/templates/passenger/passenger-add.php', compact('flights', 'passenger'));
        }

        Error::set($this->errors);
        if (!in_array('success', $this->errors)) {
            Redirect::to('/passagiers');
        }
    }

    public function edit($id): void
    {
        if (auth()->withRole(Passenger::USER_ROLE)) {
            if (auth()->user()->getId() != $id) {
                Redirect::to('/dashboard');
            }
        }

        $passenger = Passenger::find($id);
        $flights = null;

        if (!$passenger) {
            Redirect::to('/passagiers');
        }

        if (auth()->withRole(ServiceDesk::USER_ROLE)) {
            $flights = $this->flights();
        }

        View::new()->render('views/templates/passenger/passenger-edit.php', compact('flights', 'passenger'));
    }

    public function editPassenger($id): void
    {
        $_POST['passenger_id'] = $id;
        $post = $this->handlePost(CRUDAction::ACTION_UPDATE);
        $flights = $this->flights();

        if ($post) {

            $action = Passenger::where('passagiernummer', '=', $id)->update([
                'naam' => $post['name'],
                'wachtwoord' => $post['password'],
            ]);

            if ($action) {
                $this->errors['success'] = 'Passagier succesvol bewerkt';
            } else {
                $this->errors['error'] = 'Passagier kon niet worden bewerkt';
            }

            $passenger = new Passenger();
            $passenger->passagiernummer = $id;
            $passenger->naam = $post['name'];
            $passenger->geslacht = $post['gender'];
            $passenger->balienummer = $post['desk_id'];
            $passenger->stoel = $post['seat'];
            $passenger->inchecktijdstip = $post['checkin_time'];
            $passenger->wachtwoord = $post['password'];

            View::new()->render('views/templates/passenger/passenger-edit.php', compact('flights', 'passenger'));
        }

        Error::set($this->errors);
        if (empty($this->errors)) {
            Redirect::to('/passagiers');
        } elseif (isset($this->errors['success'])) {
            Redirect::to('/passagiers/' . $id);
        }
    }

    #[NoReturn] public function delete($id): void
    {
        Passenger::where('passagiernummer', '=', $id)->delete();
        Redirect::to('/passagiers');
    }

    public function handlePost(CRUDAction $action): array
    {
        $post = [];

        if (!isset($_POST['submit'])) {
            return $post;
        }

        $name = isset($_POST['name']) ? $post['name'] = Text::sanitize($_POST['name']) : false;
        $flightId = isset($_POST['flight_id']) ? $post['flight_id'] = Text::sanitize($_POST['flight_id']) : false;
        $gender = isset($_POST['gender']) ? $post['gender'] = Text::sanitize($_POST['gender']) : false;
        $deskId = false;
        $seat = isset($_POST['seat']) ? Text::sanitize($_POST['seat']) : false;
        $seat = $seat ? Text::excerpt($seat, 3) : false;
        $post['seat'] = $seat;
        $checkinTime = isset($_POST['checkin_time']) ? $post['checkin_time'] = Text::toDateTime($_POST['checkin_time']) : false;
        $password = isset($_POST['password']) ? $post['password'] = Text::hash($_POST['password']) : false;

        if (isset($_POST['desk_id'])) {
            $deskId = Text::sanitize($_POST['desk_id']);
            $post['desk_id'] = $deskId;
        }

        if (auth()->withRole(ServiceDesk::USER_ROLE)) {
            $deskId = auth()->user()->getId();
            $post['desk_id'] = $deskId;
        } else if (auth()->withRole(Passenger::USER_ROLE)) {
            $flightId = auth()->user()->getModel()->vluchtnummer;
            $post['flight_id'] = $flightId;

            $deskId = auth()->user()->getModel()->balienummer;
            $post['desk_id'] = $deskId;

            $seat = auth()->user()->getModel()->stoel;
            $post['seat'] = $seat;

            $checkinTime = auth()->user()->getModel()->inchecktijdstip;
            $post['checkin_time'] = $checkinTime;
        }

        if (!$name) {
            $this->errors['name'] = 'none given';
        }

        if (!$flightId) {
            $this->errors['flight_id'] = 'none given';
        }

        if (!$gender) {
            $this->errors['gender'] = 'none given';
        }

        if (!$deskId) {
            $this->errors['desk_id'] = 'none given';
        }

        if (!$seat) {
            $this->errors['seat'] = 'none given';
        }

        if (!$checkinTime) {
            $this->errors['checkin_time'] = 'none given';
        }

        if (!$password && $action !== CRUDAction::ACTION_UPDATE) {
            $this->errors['password'] = 'none given';
        }

        if ($action == CRUDAction::ACTION_CREATE) {
            $post['passenger_id'] = Passenger::nextPassengerId();
        }

        if ($action == CRUDAction::ACTION_UPDATE) {
            $post['passenger_id'] = $_POST['passenger_id'];
        }

        if (!auth()->withRole(Passenger::USER_ROLE)) {
            $this->check($post);
        }
        return $post;
    }

    public function check(array $data): void
    {
        $this->checkSeat($data);
        $this->checkDeparture($data);
        $this->checkFlightFull($data);
    }

    public function checkSeat(array $data): void
    {
        $seatTaken = Passenger::where('vluchtnummer', '=', $data['flight_id'])->where('stoel', '=', $data['seat'])->where('passagiernummer', '!=', $data['passenger_id'])->exists();
        if ($seatTaken) {
            $this->errors['seat'] = 'Deze stoel is al bezet';
        }
    }

    public function checkDeparture(array $data): void
    {
        $flight = Flight::with(['vluchtnummer', 'vertrektijd'])->where('vluchtnummer', '=', $data['flight_id'])->first();
        if ($flight) {
            if ($flight->vertrektijd > $data['checkin_time']) {
                $this->errors['checkin_time'] = 'OJEE! Je hebt je vlucht gemist :(';
            }
        }
    }

    public function checkFlightFull(array $data): void
    {
        $flight = Flight::where('vluchtnummer', '=', $data['flight_id'])->first();
        if ($flight?->getPassengers()->count() >= (int)$flight->max_aantal) {
            $this->errors['flight_id'] = 'Deze vlucht zit vol';
        }
    }

    private function flights()
    {
        $flights = null;
        $user = auth()->user();
        if ($user->getRole() === ServiceDesk::USER_ROLE) {
            $serviceDesk = ServiceDesk::with(['balienummer'])->where('balienummer', '=', $user->getId())->first();
            $flights = $serviceDesk->getFlights();
        }
        return $flights;
    }

}
