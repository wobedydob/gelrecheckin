<?php declare(strict_types=1);
/** @var $flight \Model\Flight */
?>

<div class="container">

    <a href="<?php echo site_url('vluchten'); ?>" class="button primary-button">Terug naar Overzicht</a>

    <div class="flight-details">
        <h1>Vluchtdetails</h1>
        <?php if ($flight): ?>
            <table class="styled-table">
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
                        <?php

                        dump($flight->vertrektijd);
                        //echo htmlspecialchars($flight->vertrektijd);

                        ?>
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