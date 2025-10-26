<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use MoonShine\Laravel\Models\MoonshineUserRole;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use MoonShine\Permissions\Traits\HasMoonShinePermissions;

/**
 * Модель пользователя
 *
 * @property int $id Идентификатор
 * @property int $unit_ulid Ulid отдела
 * @property int $moonshine_user_role_id Идентификатор роли
 * @property string $name Имя
 * @property string $email Email
 * @property string $phone Телефонный номер
 * @property string $password Пароль
 * @property string|null $remember_token Токен
 * @property \Illuminate\Support\Carbon $created_at Дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at Дата обновления
 * @property \Illuminate\Support\Carbon|null $deleted_at Дата удаления
 * @property-read \App\Models\Unit|null $unit  Отдел, к которому принадлежит пользователь
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments  Комментарии, созданные пользователем
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket[] $tickets  Заявки, созданные пользователем
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket[] $ticketResponsibility  Заявки, за которые пользователь отвечает
 * @property-read \MoonShine\Laravel\Models\MoonshineUserRole $moonshineUserRole  Роль пользователя в системе MoonShine
 */
final class User extends Authenticatable
{
    use HasFactory, HasMoonShinePermissions, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'id',
        'unit_ulid',
        'moonshine_user_role_id',
        'name',
        'email',
        'password',
        'remember_token',
        'phone',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Получить отдел, к которому пренадлежит ползователь.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Получить все комментарии пользователя.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Получить все заявки пользователя.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'owner_id');
    }

    /**
     * Получить все заявки которые выполняет пользователь.
     */
    public function ticketResponsibility(): HasMany
    {
        return $this->hasMany(Ticket::class, 'responsible_id');
    }

    public function isSuperUser(): bool
    {
        return $this->moonshine_user_role_id === MoonshineUserRole::DEFAULT_ROLE_ID;
    }

    public function moonshineUserRole(): BelongsTo
    {
        return $this->belongsTo(MoonshineUserRole::class);
    }

    public function isAdmin(): bool
    {
        return $this->moonshine_user_role_id === 1;
    }
}
