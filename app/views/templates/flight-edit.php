<?php declare(strict_types=1);
/** @var $flight \Model\Flight */
?>

<div class="container center">

    <div class="card half action-bar">
        <a href="<?php echo site_url('vluchten'); ?>" class="button secondary-button">Terug</a>
        <h1 class="ml-10">Vlucht [<?php echo $flight->vluchtnummer; ?>] Beweren</h1>
    </div>

    <div class="card half">
        <?php if ($flight): ?>
            <?php view()->render('views/forms/flight-edit-form.php', compact('flight')); ?>
        <?php endif; ?>
    </div>

</div>

