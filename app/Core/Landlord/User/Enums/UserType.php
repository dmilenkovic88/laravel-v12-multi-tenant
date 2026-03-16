<?php

namespace App\Core\Landlord\User\Enums;

enum UserType: string
{
    case Client = 'client';
    case SuperAdmin = 'super_admin';

    public static function values(): array
    {
        return array_map(
            fn (self $case) => $case->value,
            self::cases()
        );
    }

    public function label(): string
    {
        return match($this) {
            self::Client => 'Client',
            self::SuperAdmin => 'Super Admin',
        };
    }

}
