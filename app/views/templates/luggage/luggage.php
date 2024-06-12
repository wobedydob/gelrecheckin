<?php declare(strict_types=1);
/** @var $luggage \Model\Luggage */

$passengerId = urlencode($luggage->passagiernummer);
$followId = urlencode($luggage->objectvolgnummer);

$backUrl = 'passagiers/' . $passengerId;
$editUrl = 'passagiers/' . $passengerId . '/bagage/' . $followId . '/bewerken';
$deleteUrl = 'passagiers/' . $passengerId . '/bagage/' . $followId . '/verwijderen';
?>

<div class="container">

    <div class="card white action-bar">
        <a href="<?php echo site_url($backUrl); ?>" class="button primary">Terug</a>
        <h1>Bagage Object details</h1>
        <div class="right">
            <?php view()-> render('views/molecules/edit-tools.php', compact('editUrl', 'deleteUrl')); ?>
        </div>
    </div>

    <div class="card white luggage-details">
        <?php if ($luggage): ?>
            <?php view()->render('views/organisms/table-model.php', ['model' => $luggage]);?>
        <?php endif; ?>
    </div>

</div>