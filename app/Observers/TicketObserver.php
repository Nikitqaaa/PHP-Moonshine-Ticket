<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\ProblemCategory;
use App\MoonShine\Resources\TicketResource;
use MoonShine\Laravel\Notifications\NotificationButton;
use MoonShine\Laravel\Notifications\MoonShineNotification;

final class TicketObserver
{
    /**
     * Отправить уведомление о новой заявке админам отдела.
     */
    public function created(Ticket $ticket): void
    {
        MoonShineNotification::send(
            "Новая заявка {$ticket->id}",
            new NotificationButton(
                'Открыть заявку',
                app(TicketResource::class)->getDetailPageUrl($ticket->id)
            ),
            User::where('unit_ulid', $ticket->unit_ulid)
                ->orWhere('moonshine_user_role_id', 1)
                ->pluck('id')
                ->toArray()
        );
    }

    /**
     * Автоматически устанавливает отдел по категории проблемы и статус заявки "Новая".
     */
    public function creating(Ticket $ticket): void
    {
        if (!$ticket->unit_ulid && $ticket->problem_category_ulid) {
            $ticket->unit_ulid = ProblemCategory::where('ulid', $ticket->problem_category_ulid)
                ->first()?->unit?->ulid;
        }

        if (!$ticket->ticket_status_ulid) {
            $ticket->ticket_status_ulid = TicketStatus::where('slug', 'new')->first()->ulid;

        }
    }

    /**
     * Уведомляет о смене статуса владельца и об изменениях админов отдела.
     */
    public function updated(Ticket $ticket): void
    {
        if ($ticket->isDirty('ticket_status_ulid')) {
            MoonShineNotification::send(
                "Статус заявки {$ticket->id} изменён на \"{$ticket->ticketStatus->name}\".",
                new NotificationButton(
                    'Открыть заявку',
                    app(TicketResource::class)->getDetailPageUrl($ticket->id)
                ),
                [$ticket->owner_id],
            );
        }

        if ($ticket->isDirty()) {
            MoonShineNotification::send(
                "Заявка {$ticket->id} изменена",
                new NotificationButton(
                    'Открыть заявку',
                    app(TicketResource::class)->getDetailPageUrl($ticket->id)
                ),
                User::where('unit_ulid', $ticket->unit_ulid)
                    ->orWhere('moonshine_user_role_id', 1)
                    ->pluck('id')
                    ->toArray()
            );
        }
    }

    /**
     * Обновляет поле Отдел, если изменена категория проблемы.
     */
    public function updating(Ticket $ticket): void
    {
        if ($ticket->isDirty('problem_category_ulid')) {
            $ticket->unit_ulid = ProblemCategory::where('ulid', $ticket->problem_category_ulid)
                ->first()?->unit?->ulid;
        }
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
