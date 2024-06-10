<?php

$passengerId = page()->get('search');
$page = page()->get('page', 1);
$orderBY = page()->get('sort', 'passagiernummer');
$orderDirection = page()->get('direction', 'ASC');

$limit = 20;
$offset = $limit * ($page - 1);

$columns =
    [
        'passagiernummer',
        'naam',
        'vluchtnummer',
        'geslacht',
        'balienummer',
        'stoel',
        'inchecktijdstip',
    ];
if($passengerId) {
    $passenger = \Model\Passenger::find($passengerId);
    $passengers = new \Entity\Collection();

    if($passenger) {
        $passengers->addToCollection($passenger);
    }

} else {
    $passengers = \Model\Passenger::with($columns)->all($limit, $offset, $orderBY, $orderDirection);
}

?>

<div class="container">

    <div class="card action-bar">

        <h1>Passagiers</h1>

        <a href="<?php echo site_url('passagiers/toevoegen'); ?>" class="button primary-button ml-10">✙</a>

    </div>


    <div class="card">

        <?php if($passengers->count() > 0): ?>

            <?php view()->render('views/molecules/table-filters.php', ['search' => $passengerId ?? '', 'orderDirection' => $orderDirection, 'searchPlaceholder' => 'Zoek op passagiernummer']); ?>

            <?php view()->render('views/organisms/passengers.php', compact('passengers'));?>

            <?php if($passengers->count() > 1): ?>
                <?php view()->render('views/molecules/pagination.php', ['collection' => $passengers]); ?>
            <?php endif; ?>

        <?php else: ?>
            <h2>Geen passagier(s) gevonden</h2>
        <?php endif; ?>

    </div>

</div>