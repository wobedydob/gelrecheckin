<?php require_once 'includes.php';

$airline = \Model\Airline::where('maatschappijcode', '=', 'AB')->first();
$airport = \Model\Airport::where('luchthavencode', '=', 'AMS')->first();
$baggage = \Model\BaggageObject::where('passagiernummer', '=', 23458)->first();
$checkinAirline = \Model\CheckInAirline::where('maatschappijcode', '=', 'AB')->first();
$checkinDestination = \Model\CheckInDestination::where('luchthavencode', '=', 'AMS')->first();
$checkinFlight = \Model\CheckInFlight::where('vluchtnummer', '=', 28761)->first();
$flight = \Model\Flight::where('vluchtnummer', '=', 28761)->first();
$gate = \Model\Gate::where('gatecode', '=', 'A')->first();
$passenger = \Model\Passenger::where('passagiernummer', '=', 23453)->first();
$serviceDesk = \Model\ServiceDesk::where('balienummer', '=', '8')->first();

//var_dump($airline);
//var_dump($airport);
//var_dump($baggage);
//var_dump($checkinAirline);
//var_dump($checkinDestination);
//var_dump($checkinFlight);
//var_dump($flight);
//var_dump($gate);
//var_dump($passenger);
//var_dump($serviceDesk);



