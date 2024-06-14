<?php

namespace Model;

/**
 * @property string $vluchtnummer
 * @property string $balienummer
 */
class CheckInFlight extends Model
{
    protected static string $table = 'IncheckenVlucht';
    protected static string $primaryKey = 'vluchtnummer';
    protected static array $columns = [
        'vluchtnummer' => 'Vluchtnummer',
        'balienummer' => 'Balienummer',
    ];
}