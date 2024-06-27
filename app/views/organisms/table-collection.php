<?php
/** @var $collection \Entity\Collection */
/** @var $url string */

$url = $url ?? '';

$model = $collection->first();
$columns = $model?->columns();
$pk = $model?->pk() ?? '';

$sort = page()->get('sort', $pk);
?>

<div class="container white container-table">
    <table class="styled-table no-shadow">
        <?php if($columns): ?>
        <thead>
            <tr>
                <?php foreach($columns as $name => $label): ?>
                    <?php $active = $sort === $name ? 'active' : ''; ?>
                    <th><a href="<?php echo page()->updateUrlParams(['sort' => $name]); ?>" class="<?php echo $active; ?>"><?php echo $label; ?></a></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <?php endif; ?>

        <?php if($collection->count() > 0): ?>
        <tbody>
        <?php foreach($collection as $model): /** @var $model \Model\Model */ ?>

            <?php $onclick = $url ?: $model->url(); ?>

            <tr onclick="window.location='<?php echo $onclick; ?>'">
                <?php foreach($model->toArray() as $value): ?>
                    <td><?php echo $value; ?></td>
                <?php endforeach; ?>
            </tr>

        <?php endforeach; ?>
        </tbody>
        <?php endif; ?>
    </table>
</div>


