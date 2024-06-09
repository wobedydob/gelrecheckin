<?php
/** @var $passenger \Model\Passenger */

$name = $passenger->naam ?? '';
?>

<div class="container secondary-container">
    <h1>Welkom <?php echo auth()->user()->getModel()?->naam; ?></h1>
</div>