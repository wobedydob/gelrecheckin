<?php
/** @var $flights \Entity\Collection */
/** @var $passenger \Model\Passenger */

use Model\Flight;
use Model\ServiceDesk;

$passenger = $passenger ?? null;
$desks = ServiceDesk::with(['balienummer'])->all();

$name = $passenger?->naam;
$passengerFlightId = $passenger?->vluchtnummer;
$gender = $passenger?->geslacht;
$serviceDeskId = $passenger?->balienummer;
$seat = $passenger?->stoel;
$checkinTime = $passenger?->inchecktijdstip;
$password = $passenger?->wachtwoord;

//if(auth()->user()->getRole() === ServiceDesk::USER_ROLE) {
//    $deskId = auth()->user()->getId();
//}
?>

<form class="edit-passenger-form" action="" method="post">

    <div class="form-group">
        <label for="name">Naam</label>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
    </div>

    <div class="form-group">
        <label for="flight_id">Vluchtnummer</label>
        <select id="flight_id" name="flight_id">
            <?php foreach ($flights as $flight): /** @var $airline \Model\Flight */ ?>
                <?php $flightId = $flight->vluchtnummer; ?>
                <option value="<?php echo $flightId; ?>" <?php echo $flightId === $passengerFlightId ? 'selected' : ''; ?>><?php echo $flight->getInformation(); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="gender">Geslacht</label>
        <select id="gender" name="gender">
            <option value="M" <?php echo $gender === 'M' ? 'selected' : ''; ?>>Man</option>
            <option value="V" <?php echo $gender === 'V' ? 'selected' : ''; ?>>Vrouw</option>
            <option value="x" <?php echo $gender === 'x' ? 'selected' : ''; ?>>Overig</option>
        </select>
    </div>

    <div class="form-group">
        <label for="desk_id">Balienummer</label>
        <?php if(!auth()->withRole(ServiceDesk::USER_ROLE)): ?>
        <select id="desk_id" name="desk_id">
            <?php foreach ($desks as $desk): /** @var $desk \Model\ServiceDesk */ ?>
                <?php $deskId = $desk->balienummer; ?>
                <option value="<?php echo $deskId; ?>" <?php echo $deskId === $serviceDeskId ? 'selected' : ''; ?>><?php echo $deskId; ?></option>
            <?php endforeach; ?>
        </select>
        <?php else: ?>
            <input type="text" id="desk_id" name="desk_id" value="<?php echo auth()->user()->getId(); ?>" disabled>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="seat">Stoel</label>
        <input type="text" id="seat" name="seat" maxlength="3" value="<?php echo $seat; ?>" required>
    </div>

    <div class="form-group">
        <label for="checkin_time">Inchecktijd</label>
        <input type="datetime-local" id="checkin_time" name="checkin_time"  value="<?php echo $checkinTime; ?>" required>
    </div>

    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input type="password" id="password" name="password" value="<?php echo $password; ?>" required>
    </div>

    <button class="button secondary" type="submit" name="submit" value="submit">Opslaan</button>
</form>