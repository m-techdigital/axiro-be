<?php

namespace App\Support;

enum OrganizationLevel: string
{
    case Personal = 'personal';
    case Team = 'team';
    case Company = 'company';
    case Enterprise = 'enterprise';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
