<?php declare(strict_types=1);
/** @var $editUrl string */
/** @var $deleteUrl string */

$editUrl = $editUrl ?? false;
$deleteUrl = $deleteUrl ?? false;
?>

<?php if($editUrl): ?>
<a href="<?php echo site_url($editUrl); ?>" class="button secondary">✎</a>
<?php endif; ?>

<?php if($deleteUrl): ?>
<a href="<?php echo site_url($deleteUrl); ?>" class="button danger">✖</a>
<?php endif; ?>
