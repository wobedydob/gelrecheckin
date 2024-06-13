<?php declare(strict_types=1);
/** @var $passenger \Model\Passenger */

/** @var $luggages \Entity\Collection|null */

use Model\Flight;

$id = urlencode($passenger->passagiernummer);

$backUrl = 'passagiers';
$editUrl = 'passagiers/' . $id . '/bewerken';
$deleteUrl = 'passagiers/' . $id . '/verwijderen';

$luggageUrl = 'passagiers/' . $id . '/bagage';

$maxWeightPP = Flight::with(['max_gewicht_pp'])->where('vluchtnummer', '=', $passenger->vluchtnummer)->first();

$luggagesWeight = 0;
foreach ($luggages as $luggage) {
    $luggagesWeight += $luggage->gewicht;
}
$disableAdd = $maxWeightPP->max_gewicht_pp <= $luggagesWeight;
?>

<div class="container container-table">

    <div class="card white action-bar">

        <a href="<?php echo site_url($backUrl); ?>"
           class="button primary"
        >Terug</a>

        <h1>Passagier</h1>

        <div class="right">
            <?php view()->render('views/molecules/edit-tools.php', compact('editUrl', 'deleteUrl')); ?>
        </div>

    </div>

    <div class="card white passenger-details">
        <?php if ($passenger): ?>
            <?php view()->render('views/organisms/table-model.php', ['model' => $passenger]); ?>
        <?php endif; ?>
    </div>


    <div class="card white">

        <div class="card white no-shadow action-bar">
            <h2>Bagage</h2>

            <?php if (!$disableAdd): ?>
            <a href="<?php echo site_url($luggageUrl . '/toevoegen'); ?>"
               class="button primary ml-10"
            >
                ✚
            </a>
            <?php else: ?>
                <p class="alert px-5">Het maximumaantal bagage is bereikt</p>
            <?php endif; ?>

        </div>

        <?php if ($luggages?->count() > 0): ?>
            <?php view()->render('views/organisms/table-collection.php', ['collection' => $luggages, 'url' => site_url($luggageUrl)]); ?>
        <?php endif; ?>

    </div>
</div>