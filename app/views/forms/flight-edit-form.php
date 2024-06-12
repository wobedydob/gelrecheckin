<?php declare(strict_types=1);
/** @var $flight \Model\Flight */

$gates = Model\Gate::all();
$airlines = Model\Airline::with(['maatschappijcode', 'naam'])->all();
$airports = Model\Airport::with(['luchthavencode', 'naam'])->all();

$destination = $flight->bestemming;
$flightGate = $flight->gatecode;
$maxLimit = $flight->max_aantal;
$maxWeightPP = $flight->max_gewicht_pp;
$maxTotalWeight = $flight->max_totaalgewicht;
$departure = $flight->vertrektijd;
$flightAirline = $flight->maatschappijcode;
?>

<form class="edit-flight-form" action="" method="post">

    <div class="form-group">
        <label for="destination">Bestemming</label>
        <select id="destination" name="destination">
            <?php foreach ($airports as $airport): /** @var $airport \Model\Airport */ ?>
                <option value="<?php echo $airport->luchthavencode; ?>" <?php echo $airport->luchthavencode === $destination ? 'selected' : ''; ?>>
                    <?php echo $airport->naam . ' (' . $airport->luchthavencode . ')'; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="gate_id">Gatecode</label>
        <select id="gate_id" name="gate_id">
            <?php foreach ($gates as $gate): /** @var $gate \Model\Gate */ ?>
                <?php $gateCode = $gate->gatecode; ?>
                <option value="<?php echo $gateCode; ?>" <?php echo $gateCode === $flightGate ? 'selected' : ''; ?>>
                    <?php echo $gateCode; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="max_limit">Maximaal Aantal Passagiers</label>
        <input type="number" id="max_limit" name="max_limit" value="<?php echo $maxLimit; ?>" required>
    </div>

    <div class="form-group">
        <label for="max_weight_pp">Maximaal Gewicht per Passagier (kg)</label>
        <input type="number" id="max_weight_pp" name="max_weight_pp" value="<?php echo $maxWeightPP; ?>" required>
    </div>

    <div class="form-group">
        <label for="max_total_weight">Maximaal Totaalgewicht (kg)</label>
        <input type="number" id="max_total_weight" name="max_total_weight" value="<?php echo $maxTotalWeight; ?>" required>
    </div>

    <div class="form-group">
        <label for="departure_time">Vertrektijd</label>
        <input type="datetime-local" id="departure_time" name="departure_time" value="<?php echo $departure; ?>" required>
    </div>

    <div class="form-group">
        <label for="airline_id">Maatschappijcode</label>
        <select id="airline_id" name="airline_id">
            <?php foreach ($airlines as $airline): /** @var $airline \Model\Airline */ ?>
                <option value="<?php echo  $airline->maatschappijcode; ?>" <?php echo $airline->maatschappijcode === $flightAirline ? 'selected' : ''; ?>>
                    <?php echo $airline->naam . ' (' . $airline->maatschappijcode . ')'; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="button secondary" type="submit" name="submit" value="submit">Bewerken</button>
</form>