<?php declare(strict_types=1);
/** @var $flight \Model\Flight */

$backUrl = 'vluchten' . '/' . urlencode($flight->vluchtnummer);
?>

<div class="container center">

    <div class="card white half action-bar">
        <a href="<?php echo site_url($backUrl); ?>" class="button secondary">Terug</a>
        <h1 class="ml-10">Vlucht [<?php echo $flight->vluchtnummer; ?>] Bewerken</h1>
    </div>

    <div class="card white half">
        <?php if ($flight): ?>
            <?php view()->render('views/forms/flight-edit-form.php', compact('flight')); ?>
        <?php endif; ?>
    </div>

</div>

