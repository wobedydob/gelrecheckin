<?php
/** @var $user Entity\User */
?>

<div class="container center">

    <div class="card primary">
        <?php $deskId = auth()->user()->getModel()?->balienummer; ?>
        <h1><?php echo $deskId ? 'Balienummer ' . $deskId : 'Onbekend balienummer'; ?></h1>
    </div>

    <div class="card white half content-center">
        <h3 class="mb-10">Vluchten</h3>

        <p>
            wil ik een nieuwe Vlucht kunnen invoeren
            wil ik een overzicht hebben van alle vluchten en deze kunnen sorteren op tijd en luchthaven
            wil ik vluchtgegevens kunnen ophalen van alle vluchten o.b.v. een vluchtnummer
        </p>

        <a href="<?php echo site_url('vluchten'); ?>" class="button primary mt-20">Ga naar vluchten</a>
    </div>

    <div class="card white half content-center">
        <h3 class="mb-10">Passagiers</h3>

        <p>
            een nieuwe Passagier kunnen invoeren
        </p>

        <a href="<?php echo site_url('passagiers'); ?>" class="button secondary mt-20">Ga naar passagiers</a>

    </div>

    <div class="card white half content-center">
        <h3 class="mb-10">Bagage</h3>

        <p>
            wil ik de baggage van een Passagier zo efficient mogelijk kunnen inchecken
        </p>

        <a href="<?php echo site_url('bagage'); ?>" class="button dark mt-20">Ga naar bagage</a>
    </div>

</div>
