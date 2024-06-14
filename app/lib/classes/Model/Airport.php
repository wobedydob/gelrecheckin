<?php

namespace Model;

/**
 * @property string $luchthavencode
 * @property string $naam
 * @property string $land
 */
class Airport extends Model
{
    protected static string $table = 'Luchthaven';
    protected static string $primaryKey = 'luchthavencode';
    protected static array $columns = [
        'luchthavencode' => 'Luchthavencode',
        'naam' => 'Naam',
        'land' => 'Land',
    ];
}