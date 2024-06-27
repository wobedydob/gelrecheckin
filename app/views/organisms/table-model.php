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

                <?php if (auth()->withRole(\Model\ServiceDesk::USER_ROLE) && $model instanceof \Model\Passenger && $name === 'vluchtnummer'): ?>

                    <?php $flightUrl = \Model\Flight::where('vluchtnummer', '=', $model->vluchtnummer)->first()->url(); ?>
                    <tr onclick="window.location='<?php echo $flightUrl; ?>'">

                        <th><?php echo $label; ?></th>
                        <?php if(isset($model->$name)): ?>
                            <td><?php echo htmlspecialchars($model->$name); ?></td>
                        <?php else: ?>
                            <td>Onbekend</td>
                        <?php endif; ?>

                    </tr>
                <?php else: ?>

                    <tr>
                        <th><?php echo $label; ?></th>
                        <?php if(isset($model->$name)): ?>
                            <td><?php echo htmlspecialchars($model->$name); ?></td>
                        <?php else: ?>
                            <td>Onbekend</td>
                        <?php endif; ?>
                    </tr>

                <?php endif; ?>

            <?php endforeach; ?>
        </tbody>
    </table>
</div>
