<?php
/* @var $flights \Model\Flight[] */
/* @var $search string */
/* @var $limit int */
/* @var $orderDirection string */
?>

<div class="container">

    <div class="card white action-bar">

        <h1>Vluchten</h1>

        <a href="<?php echo site_url('vluchten/toevoegen'); ?>" class="button primary ml-10">✚</a>

    </div>

    <div class="card white">

        <?php if($flights->count() > 0): ?>

            <?php view()->render('views/molecules/table-filters.php', ['search' => $search ?? '', 'limit' => $limit,  'orderDirection' => $orderDirection, 'searchPlaceholder' => 'Zoek op vluchtnummer']); ?>

            <?php view()->render('views/organisms/table-collection.php', ['collection' => $flights, 'url' => site_url('vluchten')]); ?>

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