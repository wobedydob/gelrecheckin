<?php

namespace Model;

/**
 * @property string $balienummer
 * @property string $wachtwoord
 */
class ServiceDesk extends Model
{
    protected static string $table = 'Balie';

    public const string USER_ROLE = 'service_desk';
}