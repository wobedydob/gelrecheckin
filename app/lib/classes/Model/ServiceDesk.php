<?php

namespace Model;

use Entity\Collection;

/**
 * @property string $balienummer
 * @property string $wachtwoord
 */
class ServiceDesk extends Model
{
    public const string USER_ROLE = 'service_desk';

    protected static string $table = 'Balie';
    protected static string $primaryKey = 'balienummer';
    protected static array $columns = [
        'balienummer' => 'Balienummer',
        'wachtwoord' => 'Wachtwoord',
    ];

    public function getFlights(int $limit = null, int $offset = null, string $orderBY = null, string $orderDirection = 'DESC'): array|Collection|null
    {
        if (!auth()->withRole(ServiceDesk::USER_ROLE)) {
            return null;
        }

        $deskId = auth()->user()->getId();

        $flightTable = Flight::table();
        $checkinFlightTable = CheckInFlight::table();
        return Flight::join($checkinFlightTable, $checkinFlightTable . '.vluchtnummer', '=', $flightTable . '.vluchtnummer')
                     ->where($checkinFlightTable . '.balienummer', '=', $deskId)
                     ->all($limit, $offset, $orderBY, $orderDirection);
    }

    public function getPassengers(int $limit = null, int $offset = null, string $orderBY = null, string $orderDirection = 'DESC'): null|array|Collection
    {
        if (!auth()->withRole(ServiceDesk::USER_ROLE)) {
            return null;
        }

        $deskId = auth()->user()->getId();
        return Passenger::where('balienummer', '=', $deskId)->all($limit, $offset, $orderBY, $orderDirection);
    }

    public function getAirlines(int $limit = null, int $offset = null, string $orderBY = null, string $orderDirection = 'DESC'): array|Collection|null
    {
        if (!auth()->withRole(ServiceDesk::USER_ROLE)) {
            return null;
        }

        $deskId = auth()->user()->getId();
        $checkinAirlineTable = CheckInAirline::table();
        $airlineTable = Airline::table();
        return Airline::join($checkinAirlineTable, $checkinAirlineTable . '.maatschappijcode', '=', $airlineTable . '.maatschappijcode')
                      ->where($checkinAirlineTable . '.balienummer', '=', $deskId)
                      ->all($limit, $offset, $orderBY, $orderDirection);
    }

}