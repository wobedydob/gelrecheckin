<?php declare(strict_types=1);
/** @var $flights \Model\Flight[] */
?>

<div class="flights-container">
    <div class="table-container">
        <table class="styled-table">
            <thead>
            <tr>
                <th>Vluchtnummer</th>
                <th>Bestemming</th>
                <th>Gate</th>
                <th>Max. Aantal</th>
                <th>Max. Gewicht</th>
                <th>Max. Totaalgewicht</th>
                <th>Vertrektijd</th>
                <th>Maatschappij</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($flights as $flight) : ?>
                <?php $url = '/vluchten/' . urlencode($flight->vluchtnummer); ?>
                <tr onclick="window.location='<?php echo $url; ?>';">
                    <td><?php echo $flight->vluchtnummer ?></td>
                    <td><?php echo $flight->bestemming ?></td>
                    <td><?php echo $flight->gatecode ?></td>
                    <td><?php echo $flight->max_aantal ?></td>
                    <td><?php echo $flight->max_gewicht_pp ?></td>
                    <td><?php echo $flight->max_totaalgewicht ?></td>
                    <td><?php echo $flight->vertrektijd ?></td>
                    <td><?php echo $flight->maatschappijcode ?></td>
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

