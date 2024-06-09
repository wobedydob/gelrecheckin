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

    public static array $fillable = [
        'vluchtnummer',
        'bestemming',
        'gatecode',
        'max_aantal',
        'max_gewicht_pp',
        'max_totaalgewicht',
        'vertrektijd',
        'maatschappijcode'
    ];

}
