<?php

namespace Model;

/**
 * @property string $passagiernummer
 * @property string $naam
 * @property string $vluchtnummer
 * @property string $geslacht
 * @property string $balienummer
 * @property string $stoel
 * @property string $inchecktijdstip
 * @property string $wachtwoord
 */
class Passenger extends Model
{
    protected static string $table = 'Passagier';

    public const string USER_ROLE = 'passenger';
}