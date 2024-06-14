<?php

namespace Model;

/**
 * @property string $maatschappijcode
 * @property string $naam
 * @property string $max_objecten_pp
 */
class Airline extends Model
{
    protected static string $table = 'Maatschappij';
    protected static string $primaryKey = 'maatschappijcode';
    protected static array $columns = [
        'maatschappijcode' => 'Maatschappijcode',
        'naam' => 'Naam',
        'max_objecten_pp' => 'Max. Objecten PP',
    ];
}