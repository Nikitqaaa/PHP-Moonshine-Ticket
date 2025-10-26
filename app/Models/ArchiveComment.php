<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ArchiveComment extends Model
{
    use HasUlids;

    protected $primaryKey = 'ulid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'archive_comments';

    protected $fillable = [
        'ulid',
        'ticket_id',
        'user_id',
        'comment',
        'attachments',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function archiveTicket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public static function fromYear(string $year): self
    {
        $table = "archive_comments_view_{$year}";

        $model = new self;
        $model->setTable($table);

        return $model;
    }
}
