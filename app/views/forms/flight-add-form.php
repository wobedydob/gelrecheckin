<?php
$gates = Model\Gate::all();
$airlines = Model\Airline::with(['maatschappijcode', 'naam'])->all();
$airports = Model\Airport::with(['luchthavencode', 'naam'])->all();
?>

<form class="add-flight-form" action="" method="post">

    <div class="form-group">
        <label for="destination">Bestemming</label>
        <select id="destination" name="destination">
            <?php foreach ($airports as $airport): /** @var $airport \Model\Airport */ ?>
                <option value="<?php echo $airport->luchthavencode; ?>"><?php echo $airport->naam . ' (' . $airport->luchthavencode . ')'; ?></option>
            <?php endforeach; ?>
        </select>

    </div>

    <div class="form-group">
        <label for="desk_id">Gatecode</label>
        <select id="desk_id" name="desk_id">
            <?php foreach ($gates as $gate): /** @var $gate \Model\Gate */ ?>
                <?php $gateCode = $gate->gatecode; ?>
                <option value="<?php echo $gateCode; ?>"><?php echo $gateCode; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="max_limit">Maximaal Aantal Passagiers</label>
        <input type="number" id="max_limit" name="max_limit" required>
    </div>

    <div class="form-group">
        <label for="max_weight_pp">Maximaal Gewicht per Passagier (kg)</label>
        <input type="number" id="max_weight_pp" name="max_weight_pp" required>
    </div>

    <div class="form-group">
        <label for="max_total_weight">Maximaal Totaalgewicht (kg)</label>
        <input type="number" id="max_total_weight" name="max_total_weight" required>
    </div>

    <div class="form-group">
        <label for="departure_time">Vertrektijd</label>
        <input type="datetime-local" id="departure_time" name="departure_time" required>
    </div>

    <div class="form-group">
        <label for="airline_id">Maatschappijcode</label>
        <select id="airline_id" name="airline_id">
            <?php foreach ($airlines as $airline): /** @var $airline \Model\Airline */ ?>
                <option value="<?php echo  $airline->maatschappijcode; ?>"><?php echo $airline->naam . ' (' . $airline->maatschappijcode . ')'; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="button add-button" type="submit" name="submit" value="submit">Toevoegen</button>
</form>