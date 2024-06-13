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
    protected static string $primaryKey = 'passagiernummer';
    protected static array $columns = [
        'passagiernummer' => 'Passagiernummer',
        'naam' => 'Naam',
        'vluchtnummer' => 'Vluchtnummer',
        'geslacht' => 'Geslacht',
        'balienummer' => 'Balie Nummer',
        'stoel' => 'Stoel',
        'inchecktijdstip' => 'Inchecktijdstip',
    ];

    public const string USER_ROLE = 'passenger';

    public static function nextPassengerId(): string
    {
        return self::nextId(self::$primaryKey);
    }

    public static function exceedsWeightLimit(int $passengerId, string $followId, string $weight): bool
    {
        $passenger = Passenger::find($passengerId) ?? null;
        $flight = Flight::with(['max_gewicht_pp'])->where('vluchtnummer', '=', $passenger?->vluchtnummer)->first();

        $luggages = Luggage::where('passagiernummer', '=', $passenger->passagiernummer)->all();

        foreach ($luggages as $luggage) {

            if ($luggage->passagiernummer === $passengerId && $luggage->objectvolgnummer === $followId) {
                continue;
            }

            $calculation = (float)$luggage->gewicht + (float)$weight > $flight->max_gewicht_pp;
            if ($calculation) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }
}