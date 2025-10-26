<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\CommentObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

/**
 * Модель комментария к заявке
 *
 * @property string $ulid Идентификатор
 * @property int $ticket_id Идентификатор заявки
 * @property int $user_id Идентификатор пользователя
 * @property string $comment Текст комментария
 * @property string|null $attachment Вложения
 * @property \Illuminate\Support\Carbon $created_at Дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at Дата обновления
 * @property \Illuminate\Support\Carbon|null $deleted_at Дата удаления
 * @property-read \App\Models\User $user Автор комментария
 * @property-read \App\Models\Ticket $ticket Связанная заявка
 */
#[ObservedBy(CommentObserver::class)]
final class Comment extends Model
{
    use HasUlids, SoftDeletes;

    protected $primaryKey = 'ulid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'comments';

    protected $fillable = [
        'ulid',
        'ticket_id',
        'user_id',
        'comment',
        'attachment',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
