<?php

namespace Controller;

use Model\Flight;
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

        View::new()->render('views/templates/passenger.php', compact('passenger'));
    }

    public function add(): void
    {
        View::new()->render('views/templates/passenger-add.php');
    }

    public function addPassenger(): void
    {
        $post = $_POST;

        if (!isset($post['submit'])) {
            return;
        }

        $name = $post['name'] ? StringHelper::sanitize($post['name']) : false;
        $passengerId = $post['passenger_id'] ? StringHelper::sanitize($post['passenger_id']) : false;
        $gender = $post['gender'] ? StringHelper::sanitize($post['gender']) : false;
        $deskId = $post['desk_id'] ? StringHelper::sanitize($post['desk_id']) : false;
        $seat = $post['seat'] ? StringHelper::sanitize($post['seat']) : false;
        $seat = $seat ? StringHelper::excerpt($seat, 3) : false;
        $checkinTime = $post['checkin_time'] ? StringHelper::toDateTime($post['checkin_time']) : false;
        $password = $post['password'] ? StringHelper::hash($post['password']) : false;

        if (!$name) {
            $this->errors['name'] = 'none given';
        }

        if (!$passengerId) {
            $this->errors['passenger_id'] = 'none given';
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
            View::new()->render('views/templates/passenger-add.php', ['errors' => $this->errors]);
            return;
        }

        $action = Passenger::create([
            'naam' => $name,
            'vluchtnummer' => $passengerId,
            'geslacht' => $gender,
            'balienummer' => $deskId,
            'stoel' => $seat,
            'inchecktijdstip' => $checkinTime,
            'wachtwoord' => $password,
        ]);

        if(!$action) {
            $error = ['errors' => 'Passenger could not be added'];
            View::new()->render('views/templates/passenger-add.php', $error);
        }

        Redirect::to('/vluchten');
    }

    public function edit($id): void
    {
        $passenger = Passenger::find($id);

        if (!$passenger) {
            Redirect::to('/vluchten');
        }

        View::new()->render('views/templates/passenger-edit.php', compact('passenger'));
    }

    public function editPassenger($id): void
    {
        $post = $_POST;

        if (!isset($post['submit'])) {
            return;
        }

        $destination = $post['destination'] ? StringHelper::sanitize($post['destination']) : false;
        $gate = $post['gate_id'] ? StringHelper::sanitize($post['gate_id']) : false;
        $maxLimit = $post['max_limit'] ? StringHelper::sanitize($post['max_limit']) : false;
        $maxWeightPP = $post['max_weight_pp'] ? StringHelper::sanitize($post['max_weight_pp']) : false;
        $maxTotalWeight = $post['max_total_weight'] ? StringHelper::sanitize($post['max_total_weight']) : false;
        $departure = $post['departure_time'] ? StringHelper::toDateTime($post['departure_time']) : false;
        $airline = $post['airline_id'] ? StringHelper::sanitize($post['airline_id']) : false;

        if (!$destination) {
            $this->errors['destination'] = 'none given';
        }

        if (!$gate) {
            $this->errors['gate_id'] = 'none given';
        }

        if (!$maxLimit) {
            $this->errors['max_limit'] = 'none given';
        }

        if (!$maxWeightPP) {
            $this->errors['max_weight_pp'] = 'none given';
        }

        if (!$maxTotalWeight) {
            $this->errors['max_total_weight'] = 'none given';
        }

        if (!$departure) {
            $this->errors['departure_time'] = 'none given';
        }

        if (!$airline) {
            $this->errors['airline_id'] = 'none given';
        }

        if (!empty($this->errors)) {
            View::new()->render('views/forms/passenger-edit-form.php', ['errors' => $this->errors]);
            return;
        }

        $action = \Model\Passenger::where('vluchtnummer', '=', $id)->update([
            'vluchtnummer' => $id,
            'bestemming' => $destination,
            'gatecode' => $gate,
            'max_aantal' => $maxLimit,
            'max_gewicht_pp' => $maxWeightPP,
            'max_totaalgewicht' => $maxTotalWeight,
            'vertrektijd' => $departure,
            'maatschappijcode' => $airline,
        ]);

        if(!$action) {
            $error = ['errors' => 'Passenger could not be edited'];
            View::new()->render('views/templates/passenger-add.php', $error);
        }

        Redirect::to('/vluchten');
    }

    public function delete($id): void
    {
        Passenger::where('vluchtnummer', '=', $id)->delete();
        Redirect::to('/vluchten');
    }
    
}
