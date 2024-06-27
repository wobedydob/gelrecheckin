<?php declare(strict_types=1);
/** @var $passenger \Model\Passenger */

/** @var $luggages \Entity\Collection */

use Model\Flight;

$id = urlencode($passenger->passagiernummer);

$backUrl = 'passagiers';
$editUrl = 'passagiers/' . $id . '/bewerken';
$deleteUrl = 'passagiers/' . $id . '/verwijderen';

$luggageUrl = 'passagiers/' . $id . '/bagage';

$flight = Flight::where('vluchtnummer', '=', $passenger->vluchtnummer)->first();
$maxWeightPP = $flight->max_gewicht_pp;

$luggagesWeight = 0;
foreach ($luggages as $luggage) {
    $luggagesWeight += $luggage->gewicht;
}

$disableAdd = $maxWeightPP <= $luggagesWeight;
?>

<div class="container container-table">

    <div class="card white action-bar">

        <a href="<?php echo site_url('dashboard'); ?>"
           class="button secondary"
        >Naar dashboard</a>

        <h1>Bagage</h1>

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

    <?php if ($luggages): ?>

        <?php if ($luggages?->count() > 0): ?>

            <div class="card white passenger-details">
                <?php view()->render('views/organisms/table-collection.php', ['collection' => $luggages, 'url' => site_url($luggageUrl)]); ?>
            </div>

        <?php endif; ?>

    <?php endif; ?>

</div>