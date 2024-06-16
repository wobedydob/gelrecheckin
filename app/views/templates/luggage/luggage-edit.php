<?php declare(strict_types=1);
/** @var $luggage \Model\Luggage */
/** @var $passenger \Model\Passenger */
?>

<div class="container center">

    <div class="card white half action-bar">
        <a href="<?php echo site_url('vluchten'); ?>" class="button secondary">Terug</a>
        <h1 class="ml-10">Bagage van <?php echo $passenger->naam; ?> bewerken</h1>
    </div>

    <div class="card white half">

        <h2>Volgnummer: <?php echo $luggage->objectvolgnummer; ?></h2>

        <?php if ($luggage): ?>
            <?php view()->render('views/forms/model-luggage-form.php', compact('luggage', 'passenger')); ?>
        <?php endif; ?>
    </div>

</div>

