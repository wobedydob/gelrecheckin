<?php

$page = page()->get('page', 1);
$orderBY = page()->get('sort', 'vluchtnummer');
$orderDirection = page()->get('direction', 'ASC');

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

$flights = \Model\Flight::with($columns)->all($limit, $offset, $orderBY, $orderDirection);
?>

    <main>

        <h1>Vluchten</h1>

        <a href="<?php echo site_url('vluchten/add'); ?>" class="button add-flight-button">Vlucht Toevoegen</a>

        <hr>

        <a href="<?php echo page()->updateUrlParams(['direction' => 'ASC']); ?>" class="button sort-asc <?php if($orderDirection == 'ASC'):?> active<?php endif; ?>" >▲</a>
        <a href="<?php echo page()->updateUrlParams(['direction' => 'DESC']); ?>" class="button sort-desc <?php if($orderDirection == 'DESC'):?> active<?php endif; ?>">▼</a>


        <a href="<?php echo page()->url(); ?>" style="margin-top: 20px; margin-bottom: 5px;" class="button remove-filters">Filters verwijderen</a>

        <?php view()->render('views/organisms/flights.php', compact('flights')); ?>

        <?php view()->render('views/molecules/pagination.php', ['collection' => $flights]); ?>
    </main>

<?php


?>