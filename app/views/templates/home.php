<?php declare(strict_types=1); ?>


<div class="container welcome-container">
    <?php if (auth()->guest()): ?>
        <h1>Welkom bij GelreCheckin</h1>

        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.
            Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum.
            Praesent mauris. Fusce nec tellus sed augue semper porta.
            Mauris massa. Vestibulum lacinia arcu eget nulla.
        </p>
    <?php endif; ?>

    <?php if (auth()->withRole('passenger')): ?>
        <h1>Welkom <?php echo auth()->user()->getModel()?->naam; ?></h1>
    <?php endif; ?>

    <?php if (auth()->withRole('service_desk')): ?>
        <?php $deskId = auth()->user()->getModel()?->balienummer; ?>
        <h1><?php echo $deskId ? 'Balienummer ' . $deskId : 'Onbekend balienummer'; ?></h1>
    <?php endif; ?>
</div>

<div class="half-container content-center">
    <div class="card">
        <h3>Passagier</h3>
        <p>
            Wilt u uw vlucht- en/of bagage informatie vinden, dan
            kunt u hier inloggen op basis van uw passagiersnummer en wachtwoord.
        </p>

        <br>

        <a href="" class="button secondary-button">Ik ben een passagier</a>

    </div>
    <div class="card">
        <h3>Medewerker</h3>
        <p>Bent u een van de baliemedewerkers, log dan hier in</p>
        <br>
        <br>

        <a href="" class="button primary-button">Ik ben een medewerker</a>
    </div>
</div>

<div class="container alert-container">

    <h1>Er wordt druk gewerkt aan een nieuw uiterlijk van ons Systeem!</h1>

    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
        Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.
        Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum.
        Praesent mauris. Fusce nec tellus sed augue semper porta.
        Mauris massa. Vestibulum lacinia arcu eget nulla.
    </p>
</div>

