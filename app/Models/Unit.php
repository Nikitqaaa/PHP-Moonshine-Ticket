<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\FilterUnitForUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

/**
 * Модель отдела
 *
 * @property string $ulid Идентификатор Ulid
 * @property string $name Наименование
 * @property \Illuminate\Support\Carbon $created_at Дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at Дата обновления
 * @property \Illuminate\Support\Carbon|null $deleted_at Дата удаления
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProblemCategory[] $problemCategories Категории проблем
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket[] $tickets Заявки
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users Пользователи
 */
#[ScopedBy(FilterUnitForUser::class)]
final class Unit extends Model
{
    use HasUlids, SoftDeletes;

    protected $primaryKey = 'ulid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'units';

    protected $fillable = [
        'ulid',
        'name',
    ];

    /**
     * Получите все категории проблем для отдела.
     */
    public function problemCategories(): HasMany
    {
        return $this->hasMany(ProblemCategory::class);
    }

    /**
     * Получить все заявки для отдела.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Получить всех пользователей отдела.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
