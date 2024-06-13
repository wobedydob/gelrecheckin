<?php

namespace Enums;

enum CRUDAction
{
    case ACTION_CREATE;
    case ACTION_READ;
    case ACTION_UPDATE;
    case ACTION_DELETE;

//    public function getAction(): string
//    {
//        return match ($this) {
//            self::ACTION_CREATE => 'create',
//            self::ACTION_READ => 'read',
//            self::ACTION_UPDATE => 'update',
//            self::ACTION_DELETE => 'delete',
//        };
//    }
}
