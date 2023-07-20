<?php

namespace Sven\ArtisanView\CRUD;

enum ViewType: string
{
    case INDEX = 'index';
    case CREATE = 'create';
    case EDIT = 'edit';
    case SHOW = 'show';
    case DELETE = 'delete';
    case RESOURCE = 'resource';

    public static function toArray(): array
    {
        return [
            self::INDEX->value,
            self::CREATE->value,
            self::EDIT->value,
            self::SHOW->value,
            self::DELETE->value
        ];
    }
}
