<?php declare(strict_types=1);
/** @var $flight \Model\Flight */
?>

<div class="container container-table">

    <div class="card white action-bar">

        <a href="<?php echo site_url('dashboard'); ?>"
           class="button secondary"
        >Naar dashboard</a>

        <h1>Vluchtgegevens</h1>

    </div>

    <?php if ($flight): ?>
        <div class="card white passenger-details">
            <?php view()->render('views/organisms/table-model.php', ['model' => $flight]); ?>
        </div>
    <?php endif; ?>

</div>