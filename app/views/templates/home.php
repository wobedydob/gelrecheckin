<?php declare(strict_types=1); ?>

<main>

    <?php if (auth()->guest()): ?>
        <h1>Hello world</h1>
    <?php endif; ?>

    <?php if (auth()->withRole('passenger')): ?>
        <h1>Welkom <?php echo auth()->user()->getModel()?->naam; ?></h1>
    <?php endif; ?>

    <p>This is basic content...</p>

</main>

