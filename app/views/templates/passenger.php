<?php declare(strict_types=1);
/** @var $passenger \Model\Passenger */

$backUrl = 'passagiers';
$editUrl = 'passagiers/' . urlencode($passenger->passagiernummer) . '/bewerken';
$deleteUrl = 'passagiers/' . urlencode($passenger->passagiernummer) . '/verwijderen';
?>

<div class="container container-table">

    <div class="card white action-bar">
        <a href="<?php echo site_url($backUrl); ?>" class="button primary">Terug</a>
        <h1>Passagier</h1>
        <div class="right">
            <?php view()-> render('views/molecules/edit-tools.php', compact('editUrl', 'deleteUrl')); ?>
        </div>
    </div>

    <div class="card white passenger-details">
        <?php if ($passenger): ?>
            <?php view()->render('views/organisms/table-model.php', ['model' => $passenger]);?>
        <?php endif; ?>
    </div>

</div>