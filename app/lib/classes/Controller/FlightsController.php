<?php

namespace Controller;

use JetBrains\PhpStorm\NoReturn;
use Model\Flight;
use Model\Passenger;
use Model\ServiceDesk;
use Service\Redirect;
use Service\View;
use Util\StringHelper;

class FlightsController
{
    private array $errors = [];

    public function handle(): void
    {
        $role = auth()->user()->getRole();
        if ($role === Passenger::USER_ROLE) {
            $this->passenger();
        } elseif ($role === ServiceDesk::USER_ROLE) {
            $this->serviceDesk();
        } else {
            Redirect::to('/logout');
        }
    }

    public function serviceDesk(): void
    {
        (new ServiceDeskController())->flights();
    }

    public function passenger(): void
    {
        View::new()->render('views/templates/passenger/passenger-flights.php');
    }

    public function show($id): void
    {
        $flight = Flight::find($id);

        if (!$flight) {
            Redirect::to('/vluchten');
        }

        View::new()->render('views/templates/flight/flight.php', compact('flight'));
    }

    public function add(): void
    {
        View::new()->render('views/templates/flight/flight-add.php');
    }

    public function addFlight(): void
    {
        $post = $this->handlePost();
        $action = false;

        if (!empty($this->errors)) {
            View::new()->render('views/templates/flight/flight-add.php', ['errors' => $this->errors]);
            return;
        }

        if ($post) {

            $action = Flight::create([
                'bestemming' => $post['destination'],
                'gatecode' => $post['gate_id'],
                'max_aantal' => $post['max_limit'],
                'max_gewicht_pp' => $post['max_weight_pp'],
                'max_totaalgewicht' => $post['max_total_weight'],
                'vertrektijd' => $post['departure_time'],
                'maatschappijcode' => $post['airline_id'],
            ]);

        }

        if (!$action) {
            $error = ['errors' => 'Flight could not be added'];
            View::new()->render('views/templates/flight/flight-add.php', $error);
            return;
        }

        Redirect::to('/vluchten');
    }

    public function edit($id): void
    {
        $flight = Flight::find($id);

        if (!$flight) {
            Redirect::to('/vluchten');
        }

        View::new()->render('views/templates/flight/flight-edit.php', compact('flight'));
    }

    public function editFlight($id): void
    {
        $post = $this->handlePost();
        $action = false;

        if (!empty($this->errors)) {
            View::new()->render('views/templates/flight/flight-add.php', ['errors' => $this->errors]);
            return;
        }

        if ($post) {

            $action = Flight::where('vluchtnummer', '=', $id)->update([
                'vluchtnummer' => $id,
                'bestemming' => $post['destination'],
                'gatecode' => $post['gate_id'],
                'max_aantal' => $post['max_limit'],
                'max_gewicht_pp' => $post['max_weight_pp'],
                'max_totaalgewicht' => $post['max_total_weight'],
                'vertrektijd' => $post['departure_time'],
                'maatschappijcode' => $post['airline_id'],
            ]);

        }

        if (!$action) {
            $error = ['errors' => 'Flight could not be edited'];
            View::new()->render('views/templates/flight-add.php', $error);
        }

        Redirect::to('/vluchten');
    }

    #[NoReturn] public function delete($id): void
    {
        Flight::where('vluchtnummer', '=', $id)->delete();
        Redirect::to('/vluchten');
    }

    public function handlePost(): array
    {
        $post = [];

        if (!isset($_POST['submit'])) {
            return [];
        }

        $destination = $_POST['destination'] ? $post['destination'] = StringHelper::sanitize($_POST['destination']) : false;
        $gate = $_POST['gate_id'] ? $post['gate_id'] = StringHelper::sanitize($_POST['gate_id']) : false;
        $maxLimit = $_POST['max_limit'] ? $post['max_limit'] = StringHelper::sanitize($_POST['max_limit']) : false;
        $maxWeightPP = $_POST['max_weight_pp'] ? $post['max_weight_pp'] = StringHelper::sanitize($_POST['max_weight_pp']) : false;
        $maxTotalWeight = $_POST['max_total_weight'] ? $post['max_total_weight'] = StringHelper::sanitize($_POST['max_total_weight']) : false;
        $departure = $_POST['departure_time'] ? $post['departure_time'] = StringHelper::toDateTime($_POST['departure_time']) : false;
        $airline = $_POST['airline_id'] ? $post['airline_id'] = StringHelper::sanitize($_POST['airline_id']) : false;

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

        return $post;
    }

}