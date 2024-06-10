<?php
/** @var $collection \Entity\Collection */
/** @var $url string */

$url = $url ?? false;
$onclick = $url ? 'window.location="' . $url : '"';

$model = $collection->first();
$columns = $model?->columns();
$pk = $model?->pk() ?? '';

$sort = page()->get('sort', $pk);
?>


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
        <?php $onclick = $url ? $url . '/' . $model->$pk : ''; ?>
        <tr onclick="window.location='<?php echo $onclick; ?>'">
            <?php foreach($model->toArray() as $value): ?>
                <td><?php echo $value; ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <?php endif; ?>
</table>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var rows = document.querySelectorAll('.styled-table tbody tr');
        rows.forEach(function (row) {
            row.style.cursor = 'pointer';
        });
    });
</script>

