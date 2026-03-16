<?php

namespace App\Core\Tenancy\Enums;

enum TenantRole: string
{

    case Admin = 'admin';
    case Member = 'member';

    public static function values(): array
    {
        return array_map(fn (self $c) => $c->value, self::cases());
    }

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Member => 'Member',

        };
    }
}
