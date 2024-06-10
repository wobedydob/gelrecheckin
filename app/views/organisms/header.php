<?php declare(strict_types=1); ?>

<nav class="navbar">
    <div class="navbar-brand">
        <a href="<?php echo site_url(); ?>">GC</a>
    </div>
    <button class="navbar-toggle" id="navbar-toggle">
        <span class="navbar-toggle-icon"></span>
    </button>
    <div class="navbar-menu" id="navbar-menu">

        <ul class="navbar-list">

            <li class="navbar-item"><a class="" href="<?php echo site_url(); ?>">Home</a></li>

            <?php if(auth()->guest()): ?>

                <?php view()->render('views/organisms/menu/guest-menu.php'); ?>

            <?php elseif(auth()->withRole(\Model\Passenger::USER_ROLE)): ?>

                <?php view()->render('views/organisms/menu/passenger-menu.php'); ?>

            <?php elseif(auth()->withRole(\Model\ServiceDesk::USER_ROLE)): ?>

                <?php view()->render('views/organisms/menu/service-desk-menu.php'); ?>

            <?php endif; ?>

            <?php if(auth()->isAuthenticated()): ?>
                <li class="navbar-item"><a class="button tertiary" href="<?php echo site_url('logout'); ?>">Uitloggen</a></li>
            <?php endif; ?>

        </ul>

    </div>
</nav>
