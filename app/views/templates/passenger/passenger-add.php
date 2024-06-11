<?php declare(strict_types=1); ?>

<div class="container center">

    <div class="card white half action-bar">
        <a href="<?php echo site_url('passagiers'); ?>" class="button secondary">Terug</a>
        <h1 class="ml-10">Passagier Toevoegen</h1>
    </div>

    <div class="card white half">
        <?php view()->render('views/forms/passenger-add-form.php'); ?>
    </div>

</div>