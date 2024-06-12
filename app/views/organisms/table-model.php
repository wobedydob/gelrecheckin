<?php
/** @var $model \Model\Model */

$columns = $model?->columns();
$array = $model->toArray();
?>

<div class="container white container-table">
    <table class="styled-table no-shadow">
        <tbody>
            <?php foreach($array as $name => $value): ?>
            <?php $label = $columns[$name] ?? $name; ?>
            <tr>
                <th><?php echo $label; ?></th>
                <?php if(isset($model->$name)): ?>
                    <td><?php echo htmlspecialchars($model->$name); ?></td>
                <?php else: ?>
                    <td>Onbekend</td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
