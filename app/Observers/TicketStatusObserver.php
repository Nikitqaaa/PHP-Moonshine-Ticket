<?php

declare(strict_types=1);

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\TicketStatus;

final class TicketStatusObserver
{
    /**
     * Handle the TicketStatus "created" event.
     */
    public function created(TicketStatus $ticketStatus): void
    {
        //
    }

    /**
     * Генерирует `slug` из `name`.
     */
    public function creating(TicketStatus $ticketStatus): void
    {
        if (empty($ticketStatus->slug)) {
            $ticketStatus->slug = Str::slug($ticketStatus->name);
        }
    }

    /**
     * Handle the TicketStatus "updated" event.
     */
    public function updated(TicketStatus $ticketStatus): void
    {
        //
    }

    /**
     * Handle the TicketStatus "deleted" event.
     */
    public function deleted(TicketStatus $ticketStatus): void
    {
        //
    }

    /**
     * Handle the TicketStatus "restored" event.
     */
    public function restored(TicketStatus $ticketStatus): void
    {
        //
    }

    /**
     * Handle the TicketStatus "force deleted" event.
     */
    public function forceDeleted(TicketStatus $ticketStatus): void
    {
        //
    }
}
