<?php
/* @var $serviceDesk \Model\ServiceDesk */
/* @var $passengers \Model\Passenger[] */
/* @var $search string */
/* @var $limit int */
/* @var $orderDirection string */
?>

<div class="container">

    <div class="card white action-bar">
        <h1>Passagiers</h1>
        <a href="<?php echo site_url('passagiers/toevoegen'); ?>" class="button primary ml-10">✚</a>
    </div>

    <div class="card white">

        <?php if($passengers->count() > 0): ?>

            <?php view()->render('views/molecules/table-filters.php', ['search' => $search ?? '', 'limit' => $limit, 'orderDirection' => $orderDirection, 'searchPlaceholder' => 'Zoek op passagiernummer']); ?>

            <?php view()->render('views/organisms/table-collection.php', ['collection' => $passengers, 'url' => site_url('passagiers')]); ?>

            <?php if($passengers->count() > 1): ?>
                <?php view()->render('views/molecules/pagination.php', ['collection' => $passengers, 'total' => $serviceDesk->getPassengers()->count()]); ?>
            <?php endif; ?>

        <?php else: ?>

            <div class="action-bar">
                <h2>Geen passagier(s) gevonden</h2>
                <a href="<?php echo page()->url(); ?>" class="button secondary">Terug naar het overzicht</a>
            </div>

        <?php endif; ?>

    </div>

</div>