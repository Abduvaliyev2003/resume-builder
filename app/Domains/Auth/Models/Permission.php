<?php

namespace App\Domains\Auth\Models;

use App\Shared\Traits\HasUUID;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasUUID;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'guard_name',
    ];
}
