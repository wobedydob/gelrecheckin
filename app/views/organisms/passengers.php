<?php declare(strict_types=1);
/** @var $passengers \Model\Passenger[] */

$active = page()->get('sort', 'passagiernummer');
?>

<div class="passengers-container">
    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'passagiernummer']); ?>">Passagiernummer</a></th>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'naam']); ?>">Naam</a></th>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'vluchtnummer']); ?>">Vluchtnummer</a></th>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'geslacht']); ?>">Geslacht</a></th>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'balienummer']); ?>">Balienummer</a></th>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'stoel']); ?>">Stoel</a></th>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => 'inchecktijdstip']); ?>">Inchecktijdstip</a></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($passengers as $passenger): /** @var $passenger Model\Passenger */ ?>
                <?php $url = '/passagier/' . urlencode($passenger->passagiernummer); ?>
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

