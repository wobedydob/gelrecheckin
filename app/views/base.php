<!DOCTYPE html>

<html id="html" class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link href="<?php echo site_url(); ?>/assets/css/style.min.css" rel="stylesheet">
    <title>GelreCheckin</title>
</head>


<body>

<main id="main">
    <?php
    use Service\Route;
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    Route::resolve($method, $path);
    ?>
</main>


</body>
</html>
