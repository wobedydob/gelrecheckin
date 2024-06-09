<?php declare(strict_types=1);
/** @var $passenger \Model\Passenger */
?>

<div class="container">

    <a href="<?php echo site_url('passagiers'); ?>" class="button primary-button">Terug naar Overzicht</a>

    <div class="passenger-details">
        <h1>Vluchtdetails</h1>
        <?php if ($passenger): ?>
            <?php view()->render('views/forms/passenger-edit-form.php', compact('passenger')); ?>
        <?php endif; ?>
    </div>
</div>