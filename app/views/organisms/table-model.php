<?php
/** @var $model \Model\Model */

$columns = $model?->columns();
$array = $model->toArray();
?>

<table class="styled-table no-shadow">
    <tbody>
        <?php foreach($array as $name => $value): ?>
            <?php $label = $columns[$name] ?? $name;?>
        <tr>
            <th><?php echo $label; ?></th>
            <td><?php echo htmlspecialchars($model->$name); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
