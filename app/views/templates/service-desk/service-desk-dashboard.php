<?php
$user = auth()->user();
?>

<h1>Dashboard - Balienummer <?php echo $user['id']; ?></h1>