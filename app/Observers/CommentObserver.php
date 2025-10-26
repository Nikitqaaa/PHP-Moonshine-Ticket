<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Comment;
use App\MoonShine\Resources\TicketResource;
use MoonShine\Laravel\Notifications\NotificationButton;
use MoonShine\Laravel\Notifications\MoonShineNotification;

final class CommentObserver
{
    /**
     *  Отправить уведомление о новом коментарии пользователю создавшему заявку
     */
    public function created(Comment $comment): void
    {
        MoonShineNotification::send(
            "Новый комментарий к заявке {$comment->ticket->id}.",
            new NotificationButton(
                'Открыть заявку',
                app(TicketResource::class)->getDetailPageUrl($comment->ticket->id)
            ),
            [$comment->ticket->owner_id],
        );
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }
}
