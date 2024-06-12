<?php declare(strict_types=1);
/** @var $luggage \Model\Luggage */

use Model\Passenger;

$passengers = Passenger::with(['passagiernummer', 'naam'])->all();

$luggage = $luggage ?? null;

$passengerId = $luggage?->passagiernummer;
$followId = $luggage?->objectvolgnummer;
$weight = $luggage?->gewicht;
?>

<form class="edit-luggage-form" action="" method="post">

    <div class="form-group">
        <label for="passenger_id">Passagier</label>
        <select id="passenger_id" name="passenger_id">
            <?php foreach ($passengers as $passenger): /** @var $passenger \Model\Passenger */ ?>
                <option value="<?php echo $passenger->passagiernummer; ?>" <?php echo $passenger->passagiernummer === $passengerId ? 'selected' : ''; ?>>
                    <?php echo $passenger->naam . ' (' . $passenger->passagiernummer . ')'; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="weight">Gewicht (in kg)</label>
        <input type="number" id="weight" name="weight" step="any" value="<?php echo $weight; ?>" required>
    </div>

    <input type="hidden" name="follow_id" value="<?php echo $followId; ?>">

    <button class="button secondary" type="submit" name="submit" value="submit">Opslaan</button>
</form>