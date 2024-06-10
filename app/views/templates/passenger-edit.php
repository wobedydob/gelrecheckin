<?php declare(strict_types=1);
/** @var $passenger \Model\Passenger */
?>

<div class="container center">

    <div class="card half action-bar">
        <a href="<?php echo site_url('passagiers'); ?>" class="button secondary-button">Terug</a>
        <h1 class="ml-10">Passagier [<?php echo $passenger->passagiernummer; ?>] Bewerken</h1>
    </div>

    <div class="card half">
        <h1>Passagier</h1>
        <?php if ($passenger): ?>
            <?php view()->render('views/forms/passenger-edit-form.php', compact('passenger')); ?>
        <?php endif; ?>
    </div>

</div>