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

$flights = \Model\Flight::with($columns)->all(30, 'vluchtnummer', 'ASC');
?>

    <main>

        <h1>Vluchten</h1>

        <a href="<?php echo site_url('vluchten/add'); ?>" class="button add-flight-button">Vlucht Toevoegen</a>

        <hr>

        <?php view()->render('views/organisms/flights.php', compact('flights')); ?>

    </main>

<?php


?>