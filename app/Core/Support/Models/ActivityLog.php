<?php

namespace App\Core\Support\Models;

use App\Core\Models\BaseLandlordModel;

class ActivityLog extends BaseLandlordModel
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'action',
        'subject_type',
        'subject_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
