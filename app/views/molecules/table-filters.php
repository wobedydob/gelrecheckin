<?php declare(strict_types=1);
/** @var $search string */
/** @var $searchPlaceholder string */
/** @var $limit string */
/** @var $limitPlaceHolder string */
/** @var $orderDirection string */

$search = $search ?? '';
$searchPlaceholder = $searchPlaceholder ?? 'Zoek op ... ';

$limit = $limit ?? '';
$limitPlaceHolder = $limitPlaceHolder ?? 'Aantal';
?>

<div class="action-bar">

    <a href="<?php echo page()->updateUrlParams(['direction' => 'ASC']); ?>" class="button secondary secondary-active-d10 <?php if($orderDirection == 'ASC'):?> active<?php endif; ?>" >▲</a>
    <a href="<?php echo page()->updateUrlParams(['direction' => 'DESC']); ?>" class="button secondary secondary-active-d10 <?php if($orderDirection == 'DESC'):?> active<?php endif; ?>">▼</a>

    <form method="GET" class="limit-form left">
        <label>
            <input class="limit-field" type="number" name="limit" placeholder="<?php echo $limitPlaceHolder; ?>" value="<?php echo $limit; ?>">
        </label>
        <button type="submit" class="button secondary secondary-l20">
            ⤶
        </button>
    </form>

    <a href="<?php echo page()->url(); ?>" class="button danger">Filters verwijderen</a>

    <form method="GET" class="search-form right">
        <label>
            <input type="text" name="search" placeholder="<?php echo $searchPlaceholder; ?>" value="<?php echo htmlspecialchars($search ?? '', ENT_QUOTES); ?>">
        </label>
        <button type="submit" class="button search">
            <?php svg()->show('search.svg'); ?>
        </button>
    </form>

</div>