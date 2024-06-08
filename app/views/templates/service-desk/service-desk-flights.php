<?php

$page = page()->get('page', 1);

$limit = 40;
$offset = $limit * $page;


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

$flights = \Model\Flight::with($columns)->all($limit, $offset, 'vluchtnummer', 'DESC');
?>

    <main>

        <h1>Vluchten</h1>

        <a href="<?php echo site_url('vluchten/add'); ?>" class="button add-flight-button">Vlucht Toevoegen</a>

        <hr>

        <?php view()->render('views/organisms/flights.php', compact('flights')); ?>

        <?php view()->render('views/molecules/pagination.php', ['collection' => $flights]); ?>
    </main>

<?php


?>