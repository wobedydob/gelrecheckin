<?php declare(strict_types=1);
/** @var $flight \Model\Flight */

$backUrl = 'vluchten';
$editUrl = 'vluchten/' . urlencode($flight->vluchtnummer) . '/bewerken';
?>

<div class="container">

    <div class="card action-bar">
        <a href="<?php echo site_url($backUrl); ?>" class="button primary">Terug</a>
        <h1>Vluchtdetails</h1>
        <div class="right">
            <?php view()-> render('views/molecules/edit-tools.php', compact('editUrl')); ?>
        </div>
    </div>

    <div class="card flight-details">
        <?php if ($flight): ?>
            <?php view()->render('views/organisms/table-model.php', ['model' => $flight]);?>
        <?php endif; ?>
    </div>

</div>