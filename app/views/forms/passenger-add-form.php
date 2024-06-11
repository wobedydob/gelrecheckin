<?php

use Model\Flight;
use Model\ServiceDesk;

$flights = Flight::with(['vluchtnummer'])->all();
$desks = ServiceDesk::with(['balienummer'])->all();
?>

<form class="add-flight-form" action="" method="post">

    <div class="form-group">
        <label for="name">Naam</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div class="form-group">
        <label for="flight_id">Vluchtnummer</label>
        <select id="flight_id" name="flight_id">
            <?php foreach ($flights as $flight): /** @var $airline \Model\Flight */ ?>
                <?php $flightId = $flight->vluchtnummer; ?>
                <option value="<?php echo $flightId; ?>"><?php echo $flightId; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="gender">Geslacht</label>
        <select id="gender" name="gender">
            <option value="M">Man</option>
            <option value="V">Vrouw</option>
            <option value="x">Overig</option>
        </select>
    </div>

    <div class="form-group">
        <label for="desk_id">Balienummer</label>
        <select id="desk_id" name="desk_id">
            <?php foreach ($desks as $desk): /** @var $desk \Model\ServiceDesk */ ?>
                <?php $deskId = $desk->balienummer; ?>
                <option value="<?php echo $deskId; ?>"><?php echo $deskId; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="seat">Stoel</label>
        <input type="text" id="seat" name="seat" maxlength="3" required>
    </div>

    <div class="form-group">
        <label for="checkin_time">Inchecktijd</label>
        <input type="datetime-local" id="checkin_time" name="checkin_time"  required>
    </div>

    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input type="password" id="password" name="password" required>
    </div>

    <button class="button add" type="submit" name="submit" value="submit">Toevoegen</button>
</form>