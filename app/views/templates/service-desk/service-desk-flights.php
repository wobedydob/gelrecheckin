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

            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.7425 10.3445H11.0325L10.7625 10.0845C11.5625 9.12449 12.0625 7.90449 12.0625 6.59449C12.0625 3.79449 9.77254 1.50449 6.97254 1.50449C4.17254 1.50449 1.88254 3.79449 1.88254 6.59449C1.88254 9.39449 4.17254 11.6845 6.97254 11.6845C8.28254 11.6845 9.50254 11.1845 10.4625 10.3845L10.7225 10.6545V11.3645L14.2925 14.9245L15.3325 13.8845L11.7425 10.3445ZM6.97254 9.90449C5.03254 9.90449 3.56254 8.43449 3.56254 6.59449C3.56254 4.65449 5.03254 3.18449 6.97254 3.18449C8.91254 3.18449 10.3825 4.65449 10.3825 6.59449C10.3825 8.53449 8.91254 9.90449 6.97254 9.90449Z" fill="#fff"/>
            </svg>

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