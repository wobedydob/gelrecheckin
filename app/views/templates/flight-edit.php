<?php declare(strict_types=1);
/** @var $flight \Model\Flight */
?>

<div class="container">

    <a href="<?php echo site_url('vluchten'); ?>" class="button primary-button">Terug naar Overzicht</a>

    <div class="flight-details">
        <h1>Vluchtdetails</h1>
        <?php if ($flight): ?>
            <?php view()->render('views/forms/flight-edit-form.php', compact('flight')); ?>
        <?php endif; ?>
    </div>
</div>