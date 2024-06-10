<?php declare(strict_types=1);
/** @var $flights \Entity\Collection */
?>

<div class="container container-transparent container-table">
    <?php view()->render('views/organisms/table-collection.php', ['collection' => $flights, 'url' => site_url('vluchten')]); ?>
</div>