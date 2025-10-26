<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\PriorityObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

/**
 * Модель приоритета
 *
 * @property string $ulid Идентификатор
 * @property string $slug Слаг
 * @property string $name Наименование
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket[] $tickets Заявки
 */
#[ObservedBy(PriorityObserver::class)]
final class Priority extends Model
{
    use HasUlids;

    protected $primaryKey = 'ulid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'priorities';

    protected $fillable = [
        'ulid',
        'slug',
        'name',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
