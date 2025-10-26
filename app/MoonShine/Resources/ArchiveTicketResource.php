<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\ArchiveTicket;
use MoonShine\Support\ListOf;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Enums\Ability;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Laravel\Resources\ModelResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\MoonShine\Pages\ArchiveTicket\ArchiveTicketFormPage;
use App\MoonShine\Pages\ArchiveTicket\ArchiveTicketIndexPage;
use App\MoonShine\Pages\ArchiveTicket\ArchiveTicketDetailPage;

#[Icon('c.ticket')]
/**
 * @extends ModelResource<ArchiveTicket, ArchiveTicketIndexPage, ArchiveTicketFormPage, ArchiveTicketDetailPage>
 */
final class ArchiveTicketResource extends ModelResource
{
    protected string $model = ArchiveTicket::class;

    protected string $title = 'Архивные заявки';

    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        $year = request('year') ?? session('archive_year');

        if ($year) {
            session(['archive_year' => $year]);
        }

        if (!$year || !request()->boolean('aggregated')) {
            return $builder->whereRaw('1 = 0');
        }

        $query = ArchiveTicket::fromYear($year)->newQuery();

        foreach (['responsible_id', 'priority_ulid', 'problem_category_ulid'] as $field) {
            if ($value = request($field)) {
                $query->where($field, $value);
            }
        }

        if ($range = request('created_at')) {
            if (!empty($range['from'])) {
                $query->whereDate('created_at', '>=', $range['from']);
            }

            if (!empty($range['to'])) {
                $query->whereDate('created_at', '<=', $range['to']);
            }
        }

        return $query->with([
            'priority', 'unit', 'owner', 'responsible', 'problemCategory', 'ticketStatus',
        ]);
    }

    /**
     * @return list<class-string<\MoonShine\Contracts\Core\PageContract>>
     */
    protected function pages(): array
    {
        return [
            ArchiveTicketIndexPage::class,
            //            AggregatedArchiveTicketFormPage::class,
            ArchiveTicketDetailPage::class,
        ];
    }

    /**
     * @param  ArchiveTicket  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

    protected function activeActions(): ListOf
    {
        return parent::activeActions()
            ->except(Action::DELETE, Action::MASS_DELETE);
    }

    public function can(Ability|string $ability): bool
    {
        return auth()->user()->isAdmin()
            || auth()->user()->isHavePermission(self::class, $ability);
    }

    public function findItem(bool $orFail = false): mixed
    {
        return ArchiveTicket::fromYear(
            request('year')
            ?? session('archive_year')
        )
            ->newQuery()
            ->findOrFail(request()->route('resourceItem'));
    }
}
