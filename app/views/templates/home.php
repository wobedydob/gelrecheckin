<?php declare(strict_types=1); ?>


<div class="container center">
    <?php if (auth()->guest()): ?>


        <div class="card primary no-hover half">

            <h1>Welkom bij GelreCheckin</h1>

            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.
                Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum.
                Praesent mauris. Fusce nec tellus sed augue semper porta.
                Mauris massa. Vestibulum lacinia arcu eget nulla.
            </p>

        </div>


        <?php view()->render('views/organisms/login-cards.php'); ?>

    <?php endif; ?>

    <?php if (auth()->withRole('passenger')): ?>

        <?php view()->render('views/templates/passenger/passenger-dashboard.php'); ?>

    <?php endif; ?>

    <?php if (auth()->withRole('service_desk')): ?>

        <?php view()->render('views/templates/service-desk/service-desk-dashboard.php'); ?>

    <?php endif; ?>


    <div class="card secondary half no-hover">

        <h1>Er wordt druk gewerkt aan een nieuw uiterlijk van ons Systeem!</h1>

        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.
            Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum.
            Praesent mauris. Fusce nec tellus sed augue semper porta.
            Mauris massa. Vestibulum lacinia arcu eget nulla.
        </p>
    </div>


</div>