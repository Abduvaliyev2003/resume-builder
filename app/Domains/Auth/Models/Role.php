<?php

namespace App\Domains\Auth\Models;

use App\Shared\Traits\HasUUID;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
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
