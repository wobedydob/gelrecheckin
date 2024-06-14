<?php

namespace Model;

/**
 * @property string $vluchtnummer
 * @property string $bestemming
 * @property string $gatecode
 * @property string $max_aantal
 * @property string $max_gewicht_pp
 * @property string $max_totaalgewicht
 * @property string $vertrektijd
 * @property string $maatschappijcode
 */
class Flight extends Model
{
    protected static string $table = 'Vlucht';
    protected static string $primaryKey = 'vluchtnummer';
    protected static array $columns = [
        'vluchtnummer' => 'Vluchtnummer',
        'bestemming' => 'Bestemming',
        'gatecode' => 'Gate',
        'max_aantal' => 'Max. Aantal',
        'max_gewicht_pp' => 'Max. Gewicht PP',
        'max_totaalgewicht' => 'Max. Totaalgewicht',
        'vertrektijd' => 'Vertrektijd',
        'maatschappijcode' => 'Maatschappij',
    ];

    public static function nextFlightId(): string
    {
        return self::nextId(self::$primaryKey);
    }

    public function getPassengers()
    {
        return Passenger::where('vluchtnummer', '=', $this->vluchtnummer)->all();
    }

    public function getDestination(): array|Model
    {
        if(!isset($this->bestemming)) {
            return [];
        }
        return Airport::find($this->bestemming);
    }

    public function getAirline(): array|Model
    {
        if(!isset($this->maatschappijcode)) {
            return [];
        }
        return Airline::find($this->maatschappijcode);
    }

    public function getInformation(): string
    {
        return '(' . $this->vluchtnummer . ') ' . $this->getAirline()->naam . ' > ' . $this->getDestination()->naam;
    }
}
