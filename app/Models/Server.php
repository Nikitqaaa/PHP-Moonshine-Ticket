<?php

declare(strict_types=1);

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Foundation\Auth\User as Authenticatable;

final class Server extends Authenticatable
{
    use HasApiTokens;
    use HasUlids;
    use SoftDeletes;

    protected $primaryKey = 'ulid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'ulid',
        'name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
