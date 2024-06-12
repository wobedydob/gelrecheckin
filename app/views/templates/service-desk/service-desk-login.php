<?php
/** @var $serviceDesks array */
/** @var $errors array */

$errors = $errors ?? [];
?>

<div class="login-container">
    <?php view()->render('views/forms/service-desk-login-form.php', compact('serviceDesks', 'errors')); ?>
</div>