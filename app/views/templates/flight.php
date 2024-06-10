<?php declare(strict_types=1);
/** @var $flight \Model\Flight */

$backUrl = 'vluchten';
$editUrl = 'vluchten/' . urlencode($flight->vluchtnummer) . '/bewerken';
$deleteUrl = 'vluchten/' . urlencode($flight->vluchtnummer) . '/verwijderen';
?>

<div class="container">

    <div class="card action-bar">
        <a href="<?php echo site_url($backUrl); ?>" class="button primary">Terug</a>
        <h1>Vluchtdetails</h1>
        <div class="right">
            <?php view()-> render('views/molecules/edit-tools.php', compact('editUrl', 'deleteUrl')); ?>
        </div>
    </div>

    <div class="card flight-details">

        <?php if ($flight): ?>
            <table class="styled-table no-shadow">
                <tbody>
                <tr>
                    <th>Vluchtnummer</th>
                    <td><?php echo htmlspecialchars($flight->vluchtnummer); ?></td>
                </tr>
                <tr>
                    <th>Bestemming</th>
                    <td><?php echo htmlspecialchars($flight->bestemming); ?></td>
                </tr>
                <tr>
                    <th>Gatecode</th>
                    <td><?php echo htmlspecialchars($flight->gatecode); ?></td>
                </tr>
                <tr>
                    <th>Maximaal aantal passagiers</th>
                    <td><?php echo htmlspecialchars($flight->max_aantal); ?></td>
                </tr>
                <tr>
                    <th>Maximaal gewicht per passagier</th>
                    <td><?php echo htmlspecialchars($flight->max_gewicht_pp); ?></td>
                </tr>
                <tr>
                    <th>Maximaal totaalgewicht</th>
                    <td><?php echo htmlspecialchars($flight->max_totaalgewicht); ?></td>
                </tr>
                <tr>
                    <th>Vertrektijd</th>
                    <td>
                        <?php echo htmlspecialchars($flight->vertrektijd) ?: 'Vertrektijd is onbekend'; ?>
                    </td>
                </tr>
                <tr>
                    <th>Maatschappijcode</th>
                    <td><?php echo htmlspecialchars($flight->maatschappijcode); ?></td>
                </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p>Geen vluchtgegevens gevonden voor vluchtnummer: <?php echo htmlspecialchars($flight->vluchtnummer); ?></p>
        <?php endif; ?>
    </div>
</div>