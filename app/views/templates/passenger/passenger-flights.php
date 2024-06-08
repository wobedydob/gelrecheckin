<?php

$columns =
    [
        'vluchtnummer',
        'bestemming',
        'gatecode',
        'max_aantal',
        'max_gewicht_pp',
        'max_totaalgewicht',
        'vertrektijd',
        'maatschappijcode'
    ];

$flights = \Model\Flight::with($columns)->all(30, 'vluchtnummer');
//dump($flights);

?>

    <main>
        <h1>Vluchten</h1>

        <hr>

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
                        <tr>
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

    </main>

<?php


?>