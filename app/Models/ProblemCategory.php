<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель категории проблемы
 *
 * @property string $ulid Идентификатор
 * @property string $unit_ulid Идентификатор отдела
 * @property string $name Наименование
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket[] $tickets Заявки
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Unit $unit Отдел
 */
final class ProblemCategory extends Model
{
    use HasUlids, SoftDeletes;

    protected string $model = ProblemCategory::class;

    protected $primaryKey = 'ulid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'ulid',
        'unit_ulid',
        'name',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_ulid', 'ulid');
    }
}
