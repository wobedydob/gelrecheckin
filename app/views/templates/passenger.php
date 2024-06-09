<?php declare(strict_types=1);
/** @var $passenger \Model\Passenger */

$editUrl = 'passagiers/' . urlencode($passenger->passagiernummer) . '/bewerken';
$deleteUrl = 'passagiers/' . urlencode($passenger->passagiernummer) . '/verwijderen';
?>

<div class="container">

    <a href="<?php echo site_url('passagiers'); ?>" class="button primary-button">Terug naar Overzicht</a>
    <a href="<?php echo site_url($editUrl); ?>" class="button secondary-button">Bewerken</a>
    <a href="<?php echo site_url($deleteUrl); ?>" class="button tertiary-button">Verwijderen</a>

    <div class="passenger-details">
        <h1>Passagierdetails</h1>
        <?php if ($passenger): ?>
            <table class="styled-table">
                <tbody>
                <tr>
                    <th>Passagiernummer</th>
                    <td><?php echo htmlspecialchars($passenger->passagiernummer); ?></td>
                </tr>
                <tr>
                    <th>Naam</th>
                    <td><?php echo htmlspecialchars($passenger->naam); ?></td>
                </tr>
                <tr>
                    <th>Vluchtnummer</th>
                    <td><?php echo htmlspecialchars($passenger->vluchtnummer); ?></td>
                </tr>
                <tr>
                    <th>Geslacht</th>
                    <td><?php echo htmlspecialchars($passenger->geslacht); ?></td>
                </tr>
                <tr>
                    <th>Balienummer</th>
                    <td><?php echo htmlspecialchars($passenger->balienummer); ?></td>
                </tr>
                <tr>
                    <th>Stoel</th>
                    <td><?php echo htmlspecialchars($passenger->stoel); ?></td>
                </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p>Geen passagiergegevens gevonden voor passagiernummer: <?php echo htmlspecialchars($passenger->passagiernummer); ?></p>
        <?php endif; ?>
    </div>
</div>