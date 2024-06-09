<?php
/** @var $user Entity\User */
?>

<div class="container primary-container">
    <?php $deskId = auth()->user()->getModel()?->balienummer; ?>
    <h1><?php echo $deskId ? 'Balienummer ' . $deskId : 'Onbekend balienummer'; ?></h1>
</div>

<div class="half-container content-center">
    <div class="card">
        <h3>Vluchten</h3>

        <br>

        <p>
            wil ik een nieuwe Vlucht kunnen invoeren
            wil ik een overzicht hebben van alle vluchten en deze kunnen sorteren op tijd en luchthaven
            wil ik vluchtgegevens kunnen ophalen van alle vluchten o.b.v. een vluchtnummer
        </p>

        <br>

        <a href="<?php echo site_url('vluchten'); ?>" class="button primary-button">Ga naar vluchten</a>
    </div>

    <div class="card">
        <h3>Passagiers</h3>

        <br>

        <p>
            een nieuwe Passagier kunnen invoeren
        </p>

        <br>

        <a href="<?php echo site_url('passagiers'); ?>" class="button secondary-button">Ga naar passagiers</a>

    </div>
</div>

<div class="half-container content-center">

    <div class="card">
        <h3>Bagage</h3>

        <br>

        <p>
            wil ik de baggage van een Passagier zo efficient mogelijk kunnen inchecken
        </p>

        <br>

        <a href="<?php echo site_url('inloggen/medewerker'); ?>" class="button dark-button">Ga naar bagage</a>
    </div>

    <div class="card">
        <h3>Uitloggen</h3>

        <br>

        <br>

        <a href="<?php echo site_url('logout'); ?>" class="button tertiary-button">Uitloggen</a>
    </div>
</div>