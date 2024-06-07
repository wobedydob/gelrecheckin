<?php
/** @var $serviceDesks array */
?>

<main>
    <h1>Login als baliemedewerker</h1>
</main>

<div class="login-container">
    <?php view()->render('views/forms/service-desk-login-form.php', compact('serviceDesks')); ?>
</div>