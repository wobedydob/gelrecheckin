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
class Passenger extends AbstractModel
{
    protected static string $table = 'Passagier';
}