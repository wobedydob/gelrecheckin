<?php declare(strict_types=1); ?>

<main>

    <a href="<?php echo site_url('vluchten'); ?>" class="back-button">Terug naar Overzicht</a>

    <div class="add-flight-container">
        <h1>Vlucht Toevoegen</h1>

        <?php view()->render('views/forms/flight-add-form.php'); ?>

    </div>

</main>