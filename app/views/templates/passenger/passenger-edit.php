<?php declare(strict_types=1);
/** @var $flights \Entity\Collection */
/** @var $passenger \Model\Passenger */

$backUrl = 'passagiers' . '/' . urlencode($passenger->passagiernummer);
?>

<div class="container center">

    <div class="card white half action-bar">
        <a href="<?php echo site_url($backUrl); ?>" class="button secondary">Terug</a>
        <h1 class="ml-10">Passagier [<?php echo $passenger->passagiernummer; ?>] Bewerken</h1>
    </div>

    <div class="card white half">
        <h1>Passagier</h1>
        <?php if ($passenger): ?>
            <?php view()->render('views/forms/model-passenger-form.php', compact('flights', 'passenger')); ?>
        <?php endif; ?>
    </div>

</div>