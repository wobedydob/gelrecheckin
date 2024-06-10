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
        'max_gewicht_pp' => 'Max. Gewicht',
        'max_totaalgewicht' => 'Max. Totaalgewicht',
        'vertrektijd' => 'Vertrektijd',
        'maatschappijcode' => 'Maatschappij',
    ];

}
