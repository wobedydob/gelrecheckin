<?php

namespace Controller;

use Model\Flight;
use Service\Page;
use Service\Redirect;
use Service\View;
use Util\StringHelper;

class FlightsController
{

    public function serviceDesk()
    {
        $post = $_POST;

        if (isset($post['search'])) {
            Page::new()->updateUrlParams(['search' => $post['search']]);
        }

        View::new()->render('views/templates/service-desk/service-desk-flights.php');
    }

    public function passenger()
    {
        View::new()->render('views/templates/passenger/passenger-flights.php');
    }

    public function show($id)
    {
        $flight = Flight::find($id);

        if (!$flight) {
            Redirect::to('/vluchten');
        }

        View::new()->render('views/templates/flight.php', compact('flight'));
    }

    public function add()
    {
        View::new()->render('views/templates/flight-add.php');
    }

    public function addFlight()
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
            View::new()->render('views/templates/flight-add.php', ['errors' => $this->errors]);
            return;
        }

        $action = \Model\Flight::create([
            'bestemming' => $destination,
            'gatecode' => $gate,
            'max_aantal' => $maxLimit,
            'max_gewicht_pp' => $maxWeightPP,
            'max_totaalgewicht' => $maxTotalWeight,
            'vertrektijd' => $departure,
            'maatschappijcode' => $airline,
        ]);

        if(!$action) {
            $error = ['errors' => 'Flight could not be added'];
            View::new()->render('views/templates/flight-add.php', $error);
        }

        Redirect::to('/vluchten');

    }

}