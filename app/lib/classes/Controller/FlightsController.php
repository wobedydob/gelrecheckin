<?php

namespace Controller;

use Enums\CRUDAction;
use JetBrains\PhpStorm\NoReturn;
use Model\CheckInFlight;
use Model\Flight;
use Model\Passenger;
use Model\ServiceDesk;
use Service\Error;
use Service\Redirect;
use Service\View;
use Util\Text;

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
        $user = auth()->user();
        $flight = Flight::where('vluchtnummer', '=', $user->getModel()->vluchtnummer)->first();
        $passenger = $user->getModel();
        View::new()->render('views/templates/passenger/passenger-flight.php', compact('passenger', 'flight'));
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
        $post = $this->handlePost(CRUDAction::ACTION_CREATE);

        if ($post) {

            $action = Flight::create([
                'vluchtnummer' => $post['flight_id'],
                'bestemming' => $post['destination'],
                'gatecode' => $post['gate_id'],
                'max_aantal' => $post['max_limit'],
                'max_gewicht_pp' => $post['max_weight_pp'],
                'max_totaalgewicht' => $post['max_total_weight'],
                'vertrektijd' => $post['departure_time'],
                'maatschappijcode' => $post['airline_id'],
            ]);

            if ($action && $this->addCheckinFlight($post['flight_id'], auth()->user()->getId())) {
                $this->errors['success'] = 'Vlucht succesvol toegevoegd';
            } else {
                $this->errors['error'] = 'Vlucht kon niet worden toegevoegd';
            }

            $flight = new Flight();
            $flight->vluchtnummer = null;
            $flight->bestemming = $post['destination'];
            $flight->gatecode = $post['gate_id'];
            $flight->max_aantal = $post['max_limit'];
            $flight->max_gewicht_pp = $post['max_weight_pp'];
            $flight->max_totaalgewicht = $post['max_total_weight'];
            $flight->vertrektijd = $post['departure_time'];
            $flight->maatschappijcode = $post['airline_id'];

            View::new()->render('views/templates/flight/flight-add.php', compact('flight'));
        }

        Error::set($this->errors);
        if (!in_array('success', $this->errors)) {
            Redirect::to('/vluchten');
        }

    }

    public function addCheckinFlight(int $flightId, int $deskId): array|bool
    {
        return CheckInFlight::create([
            'vluchtnummer' => $flightId,
            'balienummer' => $deskId
        ]);
    }

    public function editCheckinFlight(int $flightId, int $deskId): array|bool
    {
        return CheckInFlight::where('vluchtnummer', '=', $flightId)
                            ->where('balienummer', '=', $deskId)
                            ->update([
                                'vluchtnummer' => $flightId,
                                'balienummer' => $deskId
                            ]);
    }

    public function addPassenger(): void
    {
        $post = $this->handlePost(CRUDAction::ACTION_CREATE);
        $post['passenger_id'] = Passenger::nextPassengerId();

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

            if ($action && $this->editCheckinFlight($post['flight_id'], $post['desk_id'])) {
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

            View::new()->render('views/templates/passenger/passenger-add.php', compact('passenger'));
        }

        Error::set($this->errors);
        if (!in_array('success', $this->errors)) {
            Redirect::to('/passagiers');
        }
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
        $post = $this->handlePost(CRUDAction::ACTION_UPDATE);
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

    public function handlePost(CRUDAction $action): array
    {
        $post = [];

        if (!isset($_POST['submit'])) {
            return [];
        }

        $destination = $_POST['destination'] ? $post['destination'] = Text::sanitize($_POST['destination']) : false;
        $gate = $_POST['gate_id'] ? $post['gate_id'] = Text::sanitize($_POST['gate_id']) : false;
        $maxLimit = $_POST['max_limit'] ? $post['max_limit'] = Text::sanitize($_POST['max_limit']) : false;
        $maxWeightPP = $_POST['max_weight_pp'] ? $post['max_weight_pp'] = Text::sanitize($_POST['max_weight_pp']) : false;
        $maxTotalWeight = $_POST['max_total_weight'] ? $post['max_total_weight'] = Text::sanitize($_POST['max_total_weight']) : false;
        $departure = $_POST['departure_time'] ? $post['departure_time'] = Text::toDateTime($_POST['departure_time']) : false;
        $airline = $_POST['airline_id'] ? $post['airline_id'] = Text::sanitize($_POST['airline_id']) : false;

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

        if ($action == CRUDAction::ACTION_CREATE) {
            $post['flight_id'] = Flight::nextFlightId();
        }

        if ($action == CRUDAction::ACTION_UPDATE) {
            $post['flight_id'] = $_POST['flight_id'];
        }

        return $post;
    }

}