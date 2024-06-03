<?php

namespace Model;

/**
 * @property string $luchthavencode
 * @property string $naam
 * @property string $land
 */
class Airport extends AbstractModel
{
    protected static string $table = 'Luchthaven';
}