<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

final class ArchiveAggregationController extends Controller
{
    public function switchYearView(Request $request): RedirectResponse
    {
        $year = $request->input('year');

        $ticketTable = DB::table('archive_tables')
            ->where('table_original', 'tickets')
            ->get()
            ->filter(fn ($table) => date('Y', (int)$table->start_at) == $year)
            ->sortByDesc('start_at')
            ->first();

        if (!$ticketTable || !Schema::hasTable($ticketTable->table_name)) {
            return back()->with('error', 'Архивная таблица не найдена');
        }

        $viewName = "archive_tickets_view_{$year}";

        if (!$this->viewExists($viewName)) {
            DB::statement("CREATE VIEW {$viewName} AS SELECT * FROM {$ticketTable->table_name}");
        }

        $commentTable = DB::table('archive_tables')
            ->where('table_original', 'comments')
            ->get()
            ->filter(fn ($table) => date('Y', (int)$table->start_at) == $year)
            ->sortByDesc('start_at')
            ->first();

        $commentsViewName = "archive_comments_view_{$year}";

        if (!$this->viewExists($commentsViewName)) {
            DB::statement("CREATE VIEW {$commentsViewName} AS SELECT * FROM {$commentTable->table_name}");
        }

        return redirect()->route('moonshine.resource.page', [
            'resourceUri'           => 'archive-ticket-resource',
            'pageUri'               => 'archive-ticket-index-page',
            'aggregated'            => 1,
            'year'                  => $year,
            'responsible_id'        => $request->input('responsible_id'),
            'priority_ulid'         => $request->input('priority_ulid'),
            'problem_category_ulid' => $request->input('problem_category_ulid'),
            'created_at'            => [
                'from' => $request->input('created_at.from'),
                'to'   => $request->input('created_at.to'),
            ],
        ]);
    }

    protected function viewExists(string $view): bool
    {
        return DB::table('information_schema.views')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $view)
            ->exists();
    }
}
