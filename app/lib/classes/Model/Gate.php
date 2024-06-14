<?php

namespace Model;

/**
 * @property string $gatecode
 */
class Gate extends Model
{
    protected static string $table = 'Gate';
    protected static string $primaryKey = 'gatecode';
    protected static array $columns = [
        'gatecode' => 'Gatecode',
    ];
}