<?php declare(strict_types=1);
/** @var $passenger \Model\Passenger */
/** @var $luggage \Model\Luggage */

$luggage = $luggage ?? null;

if($passenger) {
    $passengerId = $passenger->passagiernummer;
} else {
    $passengerId =  $luggage?->passagiernummer;
}

$followId = $luggage?->objectvolgnummer;
$weight = $luggage?->gewicht;
?>

<form class="edit-luggage-form" action="" method="post">

    <div class="form-group">
        <label for="passenger_id">Passagier </label>
        <input type="text" id="passenger_id" name="passenger_id" value="<?php echo $passengerId; ?>" required <?php if($passengerId): ?>disabled<?php endif; ?>>
    </div>

    <div class="form-group">
        <label for="weight">Gewicht (in kg)</label>
        <input type="number" id="weight" name="weight" step="any" value="<?php echo $weight; ?>" required>
    </div>

    <input type="hidden" name="follow_id" value="<?php echo $followId; ?>">

    <button class="button secondary" type="submit" name="submit" value="submit">Opslaan</button>
</form>