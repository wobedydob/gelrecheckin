<?php declare(strict_types=1);
/** @var $passengers \Model\Passenger[] */

$active = page()->get('sort', 'passagiernummer');

$isPassenger = $active == 'passagiernummer';
$isName = $active == 'naam';
$isFlight = $active == 'vluchtnummer';
$isGender = $active == 'geslacht';
$isBaggage = $active == 'balienummer';
$isSeat = $active == 'stoel';
$isCheckinTime = $active == 'inchecktijdstip';
?>

<div class="passengers-container">
    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'passagiernummer']); ?>" class="<?php if($isPassenger):?>active<?php endif;?>">Passagiernummer</a></th>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'naam']); ?>" class="<?php if($isName):?>active<?php endif;?>">Naam</a></th>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'vluchtnummer']); ?>" class="<?php if($isFlight):?>active<?php endif;?>">Vluchtnummer</a></th>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'geslacht']); ?>" class="<?php if($isGender):?>active<?php endif;?>">Geslacht</a></th>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'balienummer']); ?>" class="<?php if($isBaggage):?>active<?php endif;?>">Balienummer</a></th>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'stoel']); ?>" class="<?php if($isSeat):?>active<?php endif;?>">Stoel</a></th>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'inchecktijdstip']); ?>" class="<?php if($isCheckinTime):?>active<?php endif;?>">Inchecktijdstip</a></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($passengers as $passenger): /** @var $passenger Model\Passenger */ ?>
                <?php $url = '/passagiers/' . urlencode($passenger->passagiernummer); ?>
                <tr onclick="window.location='<?php echo $url; ?>';">
                    <td><?php echo $passenger->passagiernummer; ?></td>
                    <td><?php echo $passenger->naam; ?></td>
                    <td><?php echo $passenger->vluchtnummer; ?></td>
                    <td><?php echo $passenger->geslacht; ?></td>
                    <td><?php echo $passenger->balienummer; ?></td>
                    <td><?php echo $passenger->stoel; ?></td>
                    <td><?php echo $passenger->inchecktijdstip ?: 'Onbekend'; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var rows = document.querySelectorAll('.styled-table tbody tr');
        rows.forEach(function (row) {
            row.style.cursor = 'pointer';
        });
    });
</script>

