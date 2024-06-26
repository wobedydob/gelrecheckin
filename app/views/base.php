<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelrecheckin</title>

    <link href="<?php echo site_url('assets/css/style.min.css'); ?>" rel="stylesheet">

</head>
<body>

<header>
    <?php view()->render('views/organisms/header.php'); ?>
</header>

<main>
    <?php render_content(); ?>
</main>

<footer>
    <?php view()->render('views/organisms/footer.php'); ?>
</footer>

<script src="<?php echo site_url('assets/js/app.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/debug.js'); ?>"></script>

</body>
</html>
