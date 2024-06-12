<?php declare(strict_types=1);
/* @var $passenger \Model\Passenger */

$backUrl = 'passagiers/' . urlencode($passenger->passagiernummer);
?>

<div class="container center">

    <div class="card white half action-bar">
        <a href="<?php echo site_url($backUrl); ?>" class="button secondary">Terug</a>
        <h1 class="ml-10">Bagage Toevoegen</h1>
    </div>

    <div class="card white half">
        <?php view()->render('views/forms/luggage-add-form.php', compact('passenger')); ?>
    </div>

</div>