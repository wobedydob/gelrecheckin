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
?>

<div class="pagination">

    <?php if(!$isFirst): ?>
    <a href="<?php echo page()->updateUrlParams(['page' => $first]);  ?>" class="button">Eerste</a>
    <a href="<?php echo page()->updateUrlParams(['page' => $prev]); ?>" class="button">Vorige</a>
    <?php endif; ?>

    <a href="<?php echo page()->urlWithParams(); ?>" class="button">Pagina <?php echo $page; ?></a>

    <?php if(!$isLast): ?>
    <a href="<?php echo page()->updateUrlParams(['page' => $next]); ?>" class="button">Volgende</a>
    <a href="<?php echo page()->updateUrlParams(['page' => $last]);  ?>" class="button">Laaste</a>
    <?php endif; ?>

</div>

