<?php declare(strict_types=1); ?>

<div class="container background-container">

    <a href="<?php echo site_url('passagiers'); ?>" class="button secondary">Terug naar Overzicht</a>

    <div class="add-flight-container">
        <h1>Passagier Toevoegen</h1>

        <?php view()->render('views/forms/passenger-add-form.php'); ?>

    </div>

</div>