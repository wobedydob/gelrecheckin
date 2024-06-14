<?php

namespace Model;

/**
 * @property string $maatschappijcode
 * @property string $balienummer
 */
class CheckInAirline extends Model
{
    protected static string $table = 'IncheckenMaatschappij';
    protected static string $primaryKey = 'maatschappijcode';
    protected static array $columns = [
        'maatschappijcode' => 'Maatschappijcode',
        'balienummer' => 'Balienummer',
    ];
}
