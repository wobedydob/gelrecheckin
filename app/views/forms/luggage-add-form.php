<?php declare(strict_types=1);
/* @var $passenger \Model\Passenger */

use Model\Passenger;

$passengerId = $passenger->passagiernummer;
$passengers = Passenger::with(['passagiernummer', 'naam'])->all();
?>

<form class="add-luggage-form" action="" method="post">

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
        <input type="number" id="weight" name="weight" step="any" value="" required>
    </div>

    <button class="button primary" type="submit" name="submit" value="submit">Toevoegen</button>
</form>
