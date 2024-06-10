<?php declare(strict_types=1);
/** @var $search string */
/** @var $searchPlaceholder string */
/** @var $orderDirection string */

$search = $search ?? '';
$searchPlaceholder = $searchPlaceholder ?? 'Zoek op ... ';
?>

<div class="action-bar">

    <a href="<?php echo page()->updateUrlParams(['direction' => 'ASC']); ?>" class="button secondary <?php if($orderDirection == 'ASC'):?> active<?php endif; ?>" >▲</a>
    <a href="<?php echo page()->updateUrlParams(['direction' => 'DESC']); ?>" class="button secondary <?php if($orderDirection == 'DESC'):?> active<?php endif; ?>">▼</a>
    <a href="<?php echo page()->url(); ?>" class="button remove-filters">Filters verwijderen</a>

    <form method="GET" class="search-form right">
        <label>
            <input type="text" name="search" placeholder="<?php echo $searchPlaceholder; ?>" value="<?php echo htmlspecialchars($search ?? '', ENT_QUOTES); ?>">
        </label>
        <button type="submit" class="button search-button">
            <?php svg()->show('search.svg'); ?>
        </button>
    </form>

</div>