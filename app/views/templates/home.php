<?php declare(strict_types=1); ?>



<?php if (auth()->guest()): ?>
    <div class="container center">

        <div class="card primary no-hover half">

            <h1>Welkom bij GelreCheckin</h1>

            <p>
                Momenteel wordt druk gewerkt aan een nieuw uiterlijk van ons Systeem. Hierdoor zal u een nieuw uiterlijk krijgen.
                Hieronder kunt u inloggen.
            </p>

        </div>

        <?php view()->render('views/organisms/login-cards.php'); ?>

    </div>
<?php endif; ?>

<?php if (auth()->withRole('passenger')): ?>

    <?php view()->render('views/templates/passenger/passenger-dashboard.php'); ?>

<?php endif; ?>

<?php if (auth()->withRole('service_desk')): ?>

    <?php view()->render('views/templates/service-desk/service-desk-dashboard.php'); ?>

<?php endif; ?>

