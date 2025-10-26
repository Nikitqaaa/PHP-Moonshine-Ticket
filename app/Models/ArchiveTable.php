<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

final class ArchiveTable extends Model
{
    use HasUlids;

    protected $primaryKey = 'ulid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'archive_tables';

    protected $fillable = [
        'ulid',
        'table_original',
        'table_name',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'start_at' => 'timestamp',
        'end_at'   => 'timestamp',
    ];
}
