<?php declare(strict_types=1);
/** @var $flights \Entity\Collection */

$active = page()->get('sort', 'vluchtnummer');
$isFlight = $active == 'vluchtnummer';
$isDestination = $active == 'bestemming';
$isGate = $active == 'gatecode';
$isMaxPassengers = $active == 'max_aantal';
$isMaxWeight = $active == 'max_gewicht_pp';
$isMaxTotalWeight = $active == 'max_totaalgewicht';
$isDepartureTime = $active == 'vertrektijd';
$isAirline = $active == 'maatschappijcode';
?>

<div class="container container-transparent container-table">
    <?php view()->render('views/organisms/model-table.php', ['collection' => $flights, 'url' => site_url('vluchten')]); ?>
</div>

<div class="container container-transparent container-table">
    <table class="styled-table no-shadow">
        <thead>
        <tr>
            <th><a href="<?php echo page()->updateUrlParams(['sort' => 'vluchtnummer']); ?>" class="<?php if($isFlight):?>active<?php endif;?>">Vluchtnummer</a></th>
            <th><a href="<?php echo page()->updateUrlParams(['sort' => 'bestemming']); ?>" class="<?php if($isDestination):?>active<?php endif;?>">Bestemming</a></th>
            <th><a href="<?php echo page()->updateUrlParams(['sort' => 'gatecode']); ?>" class="<?php if($isGate):?>active<?php endif;?>">Gate</a></th>
            <th><a href="<?php echo page()->updateUrlParams(['sort' => 'max_aantal']); ?>" class="<?php if($isMaxPassengers):?>active<?php endif;?>">Max. Aantal</a></th>
            <th><a href="<?php echo page()->updateUrlParams(['sort' => 'max_gewicht_pp']); ?>" class="<?php if($isMaxWeight):?>active<?php endif;?>">Max. Gewicht</a></th>
            <th><a href="<?php echo page()->updateUrlParams(['sort' => 'max_totaalgewicht']); ?>" class="<?php if($isMaxTotalWeight):?>active<?php endif;?>">Max. Totaalgewicht</a></th>
            <th><a href="<?php echo page()->updateUrlParams(['sort' => 'vertrektijd']); ?>" class="<?php if($isDepartureTime):?>active<?php endif;?>">Vertrektijd</a></th>
            <th><a href="<?php echo page()->updateUrlParams(['sort' => 'maatschappijcode']); ?>" class="<?php if($isAirline):?>active<?php endif;?>">Maatschappij</a></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($flights as $flight) : ?>
            <?php $url = '/vluchten/' . urlencode($flight->vluchtnummer); ?>
            <tr onclick="window.location='<?php echo $url; ?>';">
                <td><?php echo $flight->vluchtnummer; ?></td>
                <td><?php echo $flight->bestemming; ?></td>
                <td><?php echo $flight->gatecode; ?></td>
                <td><?php echo $flight->max_aantal; ?></td>
                <td><?php echo $flight->max_gewicht_pp; ?></td>
                <td><?php echo $flight->max_totaalgewicht; ?></td>
                <td><?php echo $flight->vertrektijd ?: 'Vertrektijd is onbekend'; ?></td>
                <td><?php echo $flight->maatschappijcode; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var rows = document.querySelectorAll('.styled-table tbody tr');
        rows.forEach(function (row) {
            row.style.cursor = 'pointer';
        });
    });
</script>

