<?php declare(strict_types=1);
/** @var $passengers \Entity\Collection */
?>

<div class="container container-transparent container-table">
    <?php view()->render('views/organisms/table-collection.php', ['collection' => $passengers, 'url' => site_url('passagiers')]); ?>
</div>

