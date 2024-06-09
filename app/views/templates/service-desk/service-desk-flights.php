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

<main>
    <h1>Vluchten</h1>

    <a href="<?php echo site_url('vluchten/add'); ?>" class="button add-flight-button">Vlucht Toevoegen</a>

    <form action="<?php echo site_url('vluchten'); ?>" method="GET" class="search-form">
        <label>
            <input type="text" name="search" placeholder="Zoek op vluchtnummer" value="<?php echo htmlspecialchars($flightId ?? '', ENT_QUOTES); ?>">
        </label>
        <button type="submit" class="button search-button">

            <?php \Util\SVG::show('search.svg'); ?>

        </button>
    </form>

    <hr>

    <a href="<?php echo page()->updateUrlParams(['direction' => 'ASC']); ?>" class="button sort-asc <?php if($orderDirection == 'ASC'):?> active<?php endif; ?>" >▲</a>
    <a href="<?php echo page()->updateUrlParams(['direction' => 'DESC']); ?>" class="button sort-desc <?php if($orderDirection == 'DESC'):?> active<?php endif; ?>">▼</a>
    <a href="<?php echo page()->url(); ?>" style="margin-top: 20px; margin-bottom: 5px;" class="button remove-filters">Filters verwijderen</a>

    <?php if($flights->count() > 0): ?>

        <?php view()->render('views/organisms/flights.php', compact('flights')); ?>

        <?php if($flights->count() > 1): ?>
            <?php view()->render('views/molecules/pagination.php', ['collection' => $flights]); ?>
        <?php endif; ?>

    <?php else: ?>
        <h2>Geen vluchten gevonden</h2>
    <?php endif; ?>

</main>