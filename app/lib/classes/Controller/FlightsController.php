<?php

namespace Controller;

use Model\Flight;
use Service\Redirect;
use Service\View;

class FlightsController
{

    public function serviceDesk()
    {
        View::new()->render('views/templates/service-desk/service-desk-flights.php');
    }

    public function passenger()
    {
        View::new()->render('views/templates/passenger/passenger-flights.php');
    }

    public function show($id)
    {
        $flight = Flight::find($id);

        if(!$flight) {
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

        dump($post);

        if(!empty($this->errors)) {
            View::new()->render('views/templates/flight-add.php', ['errors' => $this->errors]);
            return;
        }
    }

}
