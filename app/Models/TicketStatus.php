<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

/**
 * Модель статуса заявки
 *
 * @property string $ulid Идентификатор Ulid
 * @property string $name Наименование
 * @property string $slug Слаг
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket[] $tickets Заявки
 */
#[ObservedBy(TicketObserver::class)]
final class TicketStatus extends Model
{
    use HasUlids, SoftDeletes;

    protected $primaryKey = 'ulid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'ticket_statuses';

    protected $fillable = [
        'ulid',
        'name',
        'slug',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'ticket_status_ulid');
    }
}
