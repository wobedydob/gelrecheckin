<?php
/** @var $user Entity\User */

$name = auth()->user()->getModel()?->naam;
$passengerUrl = 'passagiers/' . auth()->user()->getId();
$luggageUrl = 'passagiers/' . auth()->user()->getId() . '/bagage';
?>

<div class="container center">

    <div class="card secondary half content-center no-hover">
        <h1><?php echo $name ? 'Hallo ' . $name : 'Onbekend'; ?></h1>

        <p>
            Beheer je volledige profiel: wijzig je persoonsgegevens, bekijk je vluchtgegevens en check je bagage direct in.
        </p>

        <a href="<?php echo site_url($passengerUrl); ?>" class="button white mt-20">Ga naar dashboard</a>
    </div>


    <div class="card white half content-center">
        <h3 class="mb-10">Vluchten</h3>

        <p>
            Bekijk hier eenvoudig al je vluchtgegevens.
        </p>

        <a href="<?php echo site_url('vluchten'); ?>" class="button primary mt-20">Ga naar vluchten</a>
    </div>

    <div class="card white half content-center">
        <h3 class="mb-10">Bagage Inchecken</h3>

        <p>
            Check snel en gemakkelijk je bagage in voor een soepele reiservaring.
        </p>

        <a href="<?php echo site_url($luggageUrl); ?>" class="button dark mt-20">Ga naar bagage</a>

    </div>

</div>