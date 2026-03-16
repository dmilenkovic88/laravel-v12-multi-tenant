<?php

namespace App\Core\Traits;

use App\Core\Enums\ReferencePrefix;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

trait HasReferenceCode
{
    protected static function bootHasReferenceCode(): void
    {
        static::creating(function (Model $model) {
            if (blank($model->code)) {
                $model->code = static::generateReferenceCode();
            }
        });
    }

    public static function generateReferenceCode(): string
    {
        $instance = new static();

        return $instance->getConnection()->transaction(function () {
            $year = now()->format('y');
            $prefix = static::getReferenceCodePrefix();
            $fullPrefix = "{$year}-{$prefix}-";

            $query = static::query();

            if (in_array(SoftDeletes::class, class_uses_recursive(static::class), true)) {
                $query->withTrashed();
            }

            $lastRecord = $query
                ->select('code')
                ->where('code', 'like', "{$fullPrefix}%")
                ->orderByDesc('code')
                ->lockForUpdate()
                ->first();

            $nextNumber = 1;

            if ($lastRecord?->code) {
                $lastSequence = (int) str($lastRecord->code)->afterLast('-')->toString();
                $nextNumber = $lastSequence + 1;
            }

            return $fullPrefix . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
        });
    }

    protected static function getReferenceCodePrefix(): string
    {
        return ReferencePrefix::fromModel(static::class)->value;
    }
}
