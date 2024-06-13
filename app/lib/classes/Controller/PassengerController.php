<?php

namespace Controller;

use Enums\CRUDAction;
use JetBrains\PhpStorm\NoReturn;
use Model\Flight;
use Model\Luggage;
use Model\Passenger;
use Service\Error;
use Service\Redirect;
use Service\Session;
use Service\View;
use Util\StringHelper;

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

        $passengerId = $post['passenger_id'] ? StringHelper::sanitize($post['passenger_id']) : false;
        $password = $post['password'] ?: false;

        $passenger = Passenger::with(['wachtwoord'])->where('passagiernummer', '=', $passengerId)->first();
        $verify = password_verify($password, $passenger?->wachtwoord ?? '');
        $secondVerify = Passenger::where('passagiernummer', '=', $passengerId)->where('wachtwoord', '=', $password)->exists(); // only because the given database has not used any hashed passwords... stupid han sometimes...

        if (!$verify && !$secondVerify) {
            $this->errors['credentials_error'] = 'Vekeerde gegevens opgegeven';
        }

        if(!empty($this->errors)) {
            Error::set($this->errors);
            View::new()->render('views/templates/passenger/passenger-login.php');
            return;
        }

        $user = ['id' => (int) $passengerId, 'role' => 'passenger'];
        Session::instance()->set('user', $user);
        Redirect::to('/dashboard');
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
        $passenger = Passenger::find($id);

        if (!$passenger) {
            Redirect::to('/passagiers');
        }

        $luggages = Luggage::with(['objectvolgnummer', 'gewicht'])->where('passagiernummer', '=', $id)->all();

        View::new()->render('views/templates/passenger/passenger.php', compact('passenger', 'luggages'));
    }

    public function add(): void
    {
        View::new()->render('views/templates/passenger/passenger-add.php');
    }

    public function addPassenger(): void
    {
        $post = $this->handlePost(CRUDAction::ACTION_CREATE);
        $post['passenger_id'] = Passenger::nextPassengerId();

        if($post) {
            $this->check($post);

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

            if($action) {
                // $this->errors['success'] = 'Passagier succesvol toegevoegd';
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

            View::new()->render('views/templates/passenger/passenger-add.php', compact('passenger'));
        }

        Error::set($this->errors);
        if(empty($this->errors)){
            Redirect::to('/passagiers');
        }
    }

    public function edit($id): void
    {
        $passenger = Passenger::find($id);

        if (!$passenger) {
            Redirect::to('/passagiers');
        }

        View::new()->render('views/templates/passenger/passenger-edit.php', compact('passenger'));
    }

    public function editPassenger($id): void
    {
        $_POST['passenger_id'] = $id;
        $post = $this->handlePost(CRUDAction::ACTION_UPDATE);

        if($post) {

            $action = Passenger::where('passagiernummer', '=', $id)->update([
                'passagiernummer' => $id,
                'naam' => $post['name'],
                'vluchtnummer' => $post['flight_id'],
                'geslacht' => $post['gender'],
                'balienummer' => $post['desk_id'],
                'stoel' => $post['seat'],
                'inchecktijdstip' => $post['checkin_time'],
                'wachtwoord' => $post['password'],
            ]);

            if($action) {
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

            View::new()->render('views/templates/passenger/passenger-edit.php', compact('passenger'));
        }


        Error::set($this->errors);
        if(empty($this->errors)){
            Redirect::to('/passagiers');
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

        $name = $_POST['name'] ? $post['name'] = StringHelper::sanitize($_POST['name']) : false;
        $flightId = $_POST['flight_id'] ? $post['flight_id'] = StringHelper::sanitize($_POST['flight_id']) : false;
        $gender = $_POST['gender'] ? $post['gender'] = StringHelper::sanitize($_POST['gender']) : false;
        $deskId = $_POST['desk_id'] ? $post['desk_id'] = StringHelper::sanitize($_POST['desk_id']) : false;
        $seat = $_POST['seat'] ? StringHelper::sanitize($_POST['seat']) : false;
        $seat = $seat ? StringHelper::excerpt($seat, 3) : false;
        $post['seat'] = $seat;
        $checkinTime = $_POST['checkin_time'] ? $post['checkin_time'] = StringHelper::toDateTime($_POST['checkin_time']) : false;
        $password = $_POST['password'] ? $post['password'] = StringHelper::hash($_POST['password']) : false;

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

        if (!$password) {
            $this->errors['password'] = 'none given';
        }

        if($action == CRUDAction::ACTION_CREATE) {
            $post['passenger_id'] = Passenger::nextPassengerId();
        }

        if($action == CRUDAction::ACTION_UPDATE) {
            $post['passenger_id'] = $_POST['passenger_id'];
        }

        $this->check($post);
        return $post;
    }
    
    public function check(array $data): void
    {
        $this->checkSeat($data);
        $this->checkDeparture($data);
    }

    public function checkSeat(array $data): void
    {
        $seatTaken = Passenger::where('vluchtnummer', '=', $data['flight_id'])->where('stoel', '=', $data['seat'])->where('passagiernummer', '!=', $data['passenger_id'])->exists();
        if($seatTaken) {
            $this->errors['seat'] = 'Deze stoel is al bezet';
        }
    }

    public function checkDeparture(array $data): void
    {
        $flight = Flight::with(['vluchtnummer', 'vertrektijd'])->where('vluchtnummer', '=', $data['flight_id'])->first();
        if($flight) {
            if($flight->vertrektijd > $data['checkin_time']) {
                $this->errors['checkin_time'] = 'OJEE! Je hebt je vlucht gemist :(';
            }
        }
    }
    
}
