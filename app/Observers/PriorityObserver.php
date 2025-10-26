<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Priority;
use Illuminate\Support\Str;

final class PriorityObserver
{
    /**
     * Handle the Priority "created" event.
     */
    public function created(Priority $priority): void
    {
        //
    }

    /**
     * Генерирует `slug` из `name`.
     */
    public function creating(Priority $priority): void
    {
        if (empty($priority->slug)) {
            $priority->slug = Str::slug($priority->name);
        }
    }

    /**
     * Handle the Priority "updated" event.
     */
    public function updated(Priority $priority): void
    {
        //
    }

    /**
     * Handle the Priority "deleted" event.
     */
    public function deleted(Priority $priority): void
    {
        //
    }

    /**
     * Handle the Priority "restored" event.
     */
    public function restored(Priority $priority): void
    {
        //
    }

    /**
     * Handle the Priority "force deleted" event.
     */
    public function forceDeleted(Priority $priority): void
    {
        //
    }
}
