<?php

namespace App\Core\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasFormattedId
{
    protected function formattedId(): Attribute
    {
        return Attribute::make(
            get: fn (): string => str_pad((string) $this->id, 6, '0', STR_PAD_LEFT),
        );
    }
}
