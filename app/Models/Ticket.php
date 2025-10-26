<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\FilterTicketForUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

/**
 * Модель заявки
 *
 * @property int $id Идентификатор заявки
 * @property string $street Улица
 * @property string $building Дом
 * @property string $flat Квартира
 * @property string $priority_ulid ULID приоритета
 * @property string $unit_ulid ULID отдела
 * @property int $owner_id ID создателя заявки
 * @property string $problem_category_ulid ULID категории проблемы
 * @property string|null $description Описание заявки
 * @property string $ticket_status_ulid ULID статуса заявки
 * @property int|null $responsible_id ID ответственного
 * @property \Illuminate\Support\Carbon $created_at Дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at Дата обновления
 * @property \Illuminate\Support\Carbon|null $deleted_at Дата удаления
 * @property-read \App\Models\Priority $priority Приоритет
 * @property-read \App\Models\Unit $unit Отдел
 * @property-read \App\Models\User $owner Создатель заявки
 * @property-read \App\Models\User|null $responsible Ответственный
 * @property-read \App\Models\ProblemCategory $problemCategory Категория проблемы
 * @property-read \App\Models\TicketStatus $ticketStatus Статус заявки
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments Комментарии
 * @property-read string $full_address Полный адрес
 */
#[ScopedBy(FilterTicketForUser::class)]
#[ObservedBy(TicketObserver::class)]
final class Ticket extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $table = 'tickets';

    protected $fillable = [
        'id',
        'street',
        'building',
        'flat',
        'priority_ulid',
        'unit_ulid',
        'owner_id',
        'problem_category_ulid',
        'description',
        'ticket_status_ulid',
        'responsible_id',
    ];

    protected $casts = [
        'id'          => 'integer',
        'description' => 'string',
    ];

    /**
     * Получить приоритет заявки.
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    /**
     * Получить отдел к которому принадлежит заявка.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Получить создателя заявки.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id')->withTrashed();
    }

    /**
     * Получить ответственного за выполнение заявки.
     */
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id')->withTrashed();
    }

    /**
     * Получить категории проблемы заявки.
     */
    public function problemCategory(): BelongsTo
    {
        return $this->belongsTo(ProblemCategory::class, 'problem_category_ulid', 'ulid');
    }

    /**
     * Получить статус заяки.
     */
    public function ticketStatus(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class);
    }

    /**
     * Получить все комментарии заявки.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Полный адрес
     */
    public function getFullAddressAttribute(): string
    {
        return "{$this->street}, д. {$this->building}, кв. {$this->flat}";
    }
}
