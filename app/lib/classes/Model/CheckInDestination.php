<?php

namespace Model;

/**
 * @property string $luchthavencode
 * @property string $balienummer
 */
class CheckInDestination extends Model
{
    protected static string $table = 'IncheckenBestemming';
    protected static string $primaryKey = 'luchthavencode';
    protected static array $columns = [
        'luchthavencode' => 'Luchthavencode',
        'balienummer' => 'Balienummer',
    ];
}