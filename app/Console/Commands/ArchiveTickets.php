<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\ArchiveTable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class ArchiveTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastArchivedDate = DB::table('archive_tables')->max('end_at')
            ?? Carbon::parse(DB::table('tickets')->min('created_at'))->timestamp;

        $startDate = Carbon::createFromTimestamp($lastArchivedDate)->format('Y-m-d H:i:s');
        $endDate = now()->format('Y-m-d H:i:s');

        $statusIds = DB::table('ticket_statuses')
            ->whereIn('name', ['Выполнена', 'Закрыта'])
            ->pluck('ulid')
            ->toArray();

        $tickets = DB::table('tickets')
            ->whereIn('ticket_status_ulid', $statusIds)
            ->where('created_at', '<=', $endDate)
            ->orderBy('created_at')
            ->get();

        foreach ($tickets as $ticket) {
            $ticketCreatedAt = Carbon::parse($ticket->created_at);

            $archiveTable = DB::table('archive_tables')
                ->where('start_at', '<=', $ticketCreatedAt->timestamp)
                ->where('end_at', '>=', $ticketCreatedAt->timestamp)
                ->where('table_original', 'tickets')
                ->first();

            $archiveTableComments = DB::table('archive_tables')
                ->where('start_at', '<=', $ticketCreatedAt->timestamp)
                ->where('end_at', '>=', $ticketCreatedAt->timestamp)
                ->where('table_original', 'comments')
                ->first();

            if (!$archiveTable) {
                $archiveTableName = 'tickets_' . Carbon::parse($startDate)->timestamp + 1 . '_' . now()->timestamp;
                $archiveCommentsTableName = 'comments_' . Carbon::parse($startDate)->timestamp + 1 . '_' . now()->timestamp;

                if (!Schema::hasTable($archiveTableName)) {
                    DB::statement("CREATE TABLE $archiveTableName LIKE tickets");
                    DB::statement("CREATE TABLE $archiveCommentsTableName LIKE comments");

                    ArchiveTable::insert([
                        'ulid'           => Str::ulid(),
                        'table_original' => 'tickets',
                        'table_name'     => $archiveTableName,
                        'start_at'       => Carbon::parse($startDate)->timestamp + 1,
                        'end_at'         => now()->timestamp,
                        'created_at'     => now(),
                    ]);

                    ArchiveTable::insert([
                        'ulid'           => Str::ulid(),
                        'table_original' => 'comments',
                        'table_name'     => $archiveCommentsTableName,
                        'start_at'       => Carbon::parse($startDate)->timestamp + 1,
                        'end_at'         => now()->timestamp,
                        'created_at'     => now(),
                    ]);
                }

                $archiveTable = (object)['table_name' => $archiveTableName];
                $archiveTableComments = (object)['table_name' => $archiveCommentsTableName];
            }

            DB::statement("INSERT INTO {$archiveTableComments->table_name} SELECT * FROM comments WHERE ticket_id = ?", [$ticket->id]);
            DB::statement("INSERT INTO {$archiveTable->table_name} SELECT * FROM tickets WHERE id = ?", [$ticket->id]);
            DB::table('comments')->where('ticket_id', $ticket->id)->delete();
            DB::table('tickets')->where('id', $ticket->id)->delete();
        }
    }
}
