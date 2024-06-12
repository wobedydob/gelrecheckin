<?php

namespace Model;

/**
 * @property string $passagiernummer
 * @property string $objectvolgnummer
 * @property string $gewicht
 */
class Luggage extends Model
{
    protected static string $table = 'BagageObject';
    protected static string $primaryKey = 'objectvolgnummer';
    protected static array $primaryKeys = ['passagiernummer', 'objectvolgnummer'];
    protected static array $columns = [
        'passagiernummer' => 'Passagiernummer',
        'objectvolgnummer' => 'Objectvolgnummer',
        'gewicht' => 'Gewicht',
    ];

}