<?php declare(strict_types=1);
/** @var $collection \Entity\Collection */

$page = page()->get('page', 1);
$total = $collection->first()->count();
$limit = $collection->getLimit();
$offset = $collection->getOffset();

$pages = ceil($total / $limit) - 1;

$next = $page + 1;
$prev = $page - 1;
$first = 1;
$last = $pages;

$isFirst = $page == $first;
$isLast = $page == $last;

$param = '?page=';
$url = page()->url();
?>

<div class="pagination">

    <?php if(!$isFirst): ?>
    <a href="<?php echo $url . $param . $first;  ?>" class="button">Eerste</a>
    <a href="<?php echo $url . $param . $prev; ?>" class="button">Vorige</a>
    <?php endif; ?>

    <a href="<?php echo $url . $param . $page; ?>" class="button">Pagina <?php echo $page; ?></a>

    <?php if(!$isLast): ?>
    <a href="<?php echo $url . $param . $next; ?>" class="button">Volgende</a>
    <a href="<?php echo $url . $param . $last;  ?>" class="button">Laaste</a>
    <?php endif; ?>

</div>

