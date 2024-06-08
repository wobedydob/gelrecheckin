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
?>

    <main>

        <h1>Vluchten</h1>
        <hr>

        <?php view()->render('views/organisms/flights.php', compact('flights')); ?>

    </main>

<?php


?>