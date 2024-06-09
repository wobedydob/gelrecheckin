<?php

$flightId = page()->get('search');
$page = page()->get('page', 1);
$orderBY = page()->get('sort', 'vluchtnummer');
$orderDirection = page()->get('direction', 'ASC');

$limit = 40;
$offset = $limit * ($page - 1);

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

if($flightId) {
    $flight = \Model\Flight::find($flightId);
    $flights = new \Entity\Collection();

    if($flight) {
        $flights->addToCollection($flight);
    }

} else {
    $flights = \Model\Flight::with($columns)->all($limit, $offset, $orderBY, $orderDirection);
}
?>

<div class="container">
    <h1>Vluchten</h1>

    <a href="<?php echo site_url('vluchten/toevoegen'); ?>" class="button primary-button">Vlucht Toevoegen</a>

    <?php view()->render('views/molecules/table-filters.php', ['search' => $flightId ?? '', 'orderDirection' => $orderDirection, 'searchPlaceholder' => 'Zoek op vluchtnummer']); ?>

    <?php if($flights->count() > 0): ?>

        <?php view()->render('views/organisms/flights.php', compact('flights')); ?>

        <?php if($flights->count() > 1): ?>
            <?php view()->render('views/molecules/pagination.php', ['collection' => $flights]); ?>
        <?php endif; ?>

    <?php else: ?>
        <h2>Geen vluchten gevonden</h2>
    <?php endif; ?>

</div>