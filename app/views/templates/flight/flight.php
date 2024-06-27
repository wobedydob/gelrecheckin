<?php declare(strict_types=1);
/** @var $flight \Model\Flight */

$backUrl = 'vluchten';
$editUrl = 'vluchten/' . urlencode($flight->vluchtnummer) . '/bewerken';
?>

<div class="container">

    <div class="card white action-bar">
        <a href="<?php echo site_url($backUrl); ?>"
           class="button primary"
        >Terug</a>
        <h1>Vluchtdetails</h1>
        <div class="right">
            <?php view()->render('views/molecules/edit-tools.php', compact('editUrl')); ?>
        </div>
    </div>

    <?php if ($flight): ?>
    <div class="card white flight-details">
        <?php view()->render('views/organisms/table-model.php', ['model' => $flight]); ?>
    </div>
    <?php endif; ?>

    <?php if ($flight->getPassengers()->count() > 0): ?>

        <div class="card white passengers">

            <h2>Passagiers</h2>
            <?php view()->render('views/organisms/table-collection.php', ['collection' => $flight->getPassengers()]); ?>
        </div>

    <?php endif; ?>

</div>