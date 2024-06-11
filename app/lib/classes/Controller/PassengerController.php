<?php

namespace Controller;

use Model\Passenger;
use Service\Redirect;
use Service\Session;
use Service\View;
use Util\StringHelper;

class PassengerController
{

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

        $serviceDesk = Passenger::with(['wachtwoord'])->where('passagiernummer', '=', $passengerId)->first();
        $verify = password_verify($password, $serviceDesk->wachtwoord);
        $secondVerify = Passenger::where('passagiernummer', '=', $passengerId)->where('wachtwoord', '=', $password)->exists(); // only because the given database has not used any hashed passwords... stupid han sometimes...

        if (!$passengerId) {
            $this->errors['passenger_id'] = 'none given';
        }

        if (!$verify && !$secondVerify) {
            $this->errors['error'] = 'invalid credentials';
        }

        if(!empty($this->errors)) {
            View::new()->render('views/templates/passenger/passenger-login.php', ['errors' => $this->errors]);
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
        View::new()->render('views/templates/service-desk/service-desk-passengers.php');
    }

    public function show($id): void
    {
        $passenger = Passenger::find($id);

        if (!$passenger) {
            Redirect::to('/passagiers');
        }

        View::new()->render('views/templates/passenger/passenger.php', compact('passenger'));
    }

    public function add(): void
    {
        View::new()->render('views/templates/passenger/passenger-add.php');
    }

    public function addPassenger(): void
    {
        $post = $_POST;

        if (!isset($post['submit'])) {
            return;
        }

        $name = $post['name'] ? StringHelper::sanitize($post['name']) : false;
        $flightId = $post['flight_id'] ? StringHelper::sanitize($post['flight_id']) : false;
        $gender = $post['gender'] ? StringHelper::sanitize($post['gender']) : false;
        $deskId = $post['desk_id'] ? StringHelper::sanitize($post['desk_id']) : false;
        $seat = $post['seat'] ? StringHelper::sanitize($post['seat']) : false;
        $seat = $seat ? StringHelper::excerpt($seat, 3) : false;
        $checkinTime = $post['checkin_time'] ? StringHelper::toDateTime($post['checkin_time']) : false;
        $password = $post['password'] ? StringHelper::hash($post['password']) : false;

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

        if (!empty($this->errors)) {
            View::new()->render('views/templates/passenger/passenger-add.php', ['errors' => $this->errors]);
            return;
        }

        $action = Passenger::create([
            'naam' => $name,
            'vluchtnummer' => $flightId,
            'geslacht' => $gender,
            'balienummer' => $deskId,
            'stoel' => $seat,
            'inchecktijdstip' => $checkinTime,
            'wachtwoord' => $password,
        ]);

        if(!$action) {
            $error = ['errors' => 'Passenger could not be added'];
            View::new()->render('views/templates/passenger/passenger-add.php', $error);
        }

        Redirect::to('/passagiers');
    }

    public function edit($id): void
    {
        $passenger = Passenger::find($id);

        if (!$passenger) {
            Redirect::to('/vluchten');
        }

        View::new()->render('views/templates/passenger/passenger-edit.php', compact('passenger'));
    }

    public function editPassenger($id): void
    {
        $post = $_POST;

        if (!isset($post['submit'])) {
            return;
        }

        $name = $post['name'] ? StringHelper::sanitize($post['name']) : false;
        $flightId = $post['flight_id'] ? StringHelper::sanitize($post['flight_id']) : false;
        $gender = $post['gender'] ? StringHelper::sanitize($post['gender']) : false;
        $deskId = $post['desk_id'] ? StringHelper::sanitize($post['desk_id']) : false;
        $seat = $post['seat'] ? StringHelper::sanitize($post['seat']) : false;
        $seat = $seat ? StringHelper::excerpt($seat, 3) : false;
        $checkinTime = $post['checkin_time'] ? StringHelper::toDateTime($post['checkin_time']) : false;
        $password = $post['password'] ? StringHelper::hash($post['password']) : false;

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

        if (!empty($this->errors)) {
            View::new()->render('views/forms/passenger-edit-form.php', ['errors' => $this->errors]);
            return;
        }

        $action = Passenger::where('passagiernummer', '=', $id)->update([
            'passagiernummer' => $id,
            'naam' => $name,
            'vluchtnummer' => $flightId,
            'geslacht' => $gender,
            'balienummer' => $deskId,
            'stoel' => $seat,
            'inchecktijdstip' => $checkinTime,
            'wachtwoord' => $password,
        ]);

        if(!$action) {
            $error = ['errors' => 'Passenger could not be added'];
            View::new()->render('views/templates/passenger/passenger-add.php', $error);
        }

        Redirect::to('/passagiers');
    }

    public function delete($id): void
    {
        Passenger::where('passagiernummer', '=', $id)->delete();
        Redirect::to('/passagiers');
    }
    
}
