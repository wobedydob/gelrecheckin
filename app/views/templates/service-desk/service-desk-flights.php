<?php

use Entity\Collection;
use Model\Flight;

$search = page()->get('search');
$page = page()->get('page', 1);
$orderBY = page()->get('sort', 'vluchtnummer');
$orderDirection = page()->get('direction', 'ASC');

$limit = page()->get('limit', 20);
$offset = $limit * ($page - 1);

$flightId = $search;
if($flightId) {
    $flight = Flight::find($flightId);
    $flights = new Collection();

    if($flight) {
        $flights->addToCollection($flight);
    }

} else {
    $flights = Flight::all($limit, $offset, $orderBY, $orderDirection);
}
?>

<div class="container">

    <div class="card white action-bar">

        <h1>Vluchten</h1>

        <a href="<?php echo site_url('vluchten/toevoegen'); ?>" class="button primary ml-10">✚</a>

    </div>

    <div class="card white">

        <?php if($flights->count() > 0): ?>

            <?php view()->render('views/molecules/table-filters.php', ['search' => $search ?? '', 'limit' => $limit,  'orderDirection' => $orderDirection, 'searchPlaceholder' => 'Zoek op vluchtnummer']); ?>

            <?php view()->render('views/organisms/flights.php', compact('flights')); ?>

            <?php if($flights->count() > 1): ?>
                <?php view()->render('views/molecules/pagination.php', ['collection' => $flights]); ?>
            <?php endif; ?>

        <?php else: ?>

            <div class="action-bar">
                <h2>Geen vluchten gevonden</h2>
                <a href="<?php echo page()->url(); ?>" class="button secondary">Terug naar het overzicht</a>
            </div>

        <?php endif; ?>

    </div>

</div>