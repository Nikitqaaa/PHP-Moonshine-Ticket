<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\TicketStatus;
use Closure;
use App\Models\User;
use App\Models\Ticket;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use App\Models\ProblemCategory;
use MoonShine\UI\Fields\Select;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Enums\Ability;
use MoonShine\Support\Enums\PageType;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\ClickAction;
use Illuminate\Database\Eloquent\Builder;
use MoonShine\Laravel\QueryTags\QueryTag;
use App\MoonShine\Pages\Ticket\TicketFormPage;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Pages\Ticket\TicketIndexPage;
use App\MoonShine\Pages\Ticket\TicketDetailPage;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;

/**
 * @extends ModelResource<Ticket, TicketIndexPage, TicketFormPage, TicketDetailPage>
 *
 * @method Ticket|null getItem()
 */
#[Icon('c.ticket')]
final class TicketResource extends ModelResource
{
    protected string $model = Ticket::class;

    protected string $title = 'Заявки';

    protected int $itemsPerPage = 25;

    protected bool $columnSelection = true;

    protected bool $createInModal = true;

    protected bool $editInModal = true;

    protected PageType|null $redirectAfterSave = PageType::INDEX;

    protected ClickAction|null $clickAction = ClickAction::DETAIL;

    /**
     * @return list<class-string<\MoonShine\Contracts\Core\PageContract>>
     */
    protected function pages(): array
    {
        return [
            TicketIndexPage::class,
            TicketFormPage::class,
            TicketDetailPage::class,
        ];
    }

    /**
     * @param  Ticket  $item
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
            ->except(Action::DELETE, Action::MASS_DELETE)
            ->except(fn ($action) => $action === Action::UPDATE && in_array($this->getItem()?->ticketStatus?->slug, ['completed', 'closed'])
            );
    }

    protected function filters(): iterable
    {
        return [
            Text::make('Номер заявки', 'id'),
            Select::make('Статус', 'ticket_status_ulid')
                ->options(TicketStatus::query()
                    ->pluck('name', 'ulid')
                    ->toArray()
                )
                ->nullable(),
            Select::make('Категория проблемы', 'problem_category_ulid')
                ->options(fn () => ProblemCategory::query()
                    ->when(auth()->user()->moonshine_user_role_id !== 1, fn ($q) => $q->where('unit_ulid', auth()->user()->unit_ulid))
                    ->pluck('name', 'ulid')
                    ->toArray()
                )
                ->nullable(),
            BelongsTo::make('Приоритет', 'priority', resource: PriorityResource::class)->nullable(),
            Select::make('Ответственный', 'responsible_id')
                ->options(fn () => User::query()
                    ->when(auth()->user()->moonshine_user_role_id !== 1, fn ($q) => $q->where('unit_ulid', auth()->user()->unit_ulid))
                    ->pluck('name', 'id')
                    ->toArray()
                )
                ->nullable(),
        ];
    }

    protected function search(): array
    {
        return ['id', 'title'];
    }

    public function queryTags(): array
    {
        return [
            QueryTag::make(
                'Все',
                fn (Builder $query) => $query
            )
                ->default()
                ->icon('document-duplicate'),

            QueryTag::make(
                'Новые',
                fn ($query) => $query->whereHas('ticketStatus', fn ($q) => $q->where('slug', 'new'))
            )
                ->icon('document-plus'),

            QueryTag::make(
                'В работе',
                fn ($query) => $query->whereHas('ticketStatus', fn ($q) => $q->where('slug', 'in_progress'))
            )
                ->icon('document-chart-bar'),

            QueryTag::make(
                'Выполненые',
                fn ($query) => $query->whereHas('ticketStatus', fn ($q) => $q->where('slug', 'completed'))
            )
                ->icon('document-check'),

            QueryTag::make(
                'Закрытые',
                fn ($query) => $query->whereHas('ticketStatus', fn ($q) => $q->where('slug', 'closed'))
            )->icon('document-text'),

            QueryTag::make(
                'Без ответственного',
                fn ($query) => $query->whereNull('responsible_id')
            )
                ->icon('user-minus'),
        ];
    }

    protected function tdAttributes(): Closure
    {
        return function (DataWrapperContract|null $data): array {
            return [
                'class' => match ($data?->getOriginal()->ticketStatus?->slug) {
                    'in_progress' => 'bgc-blue',
                    'completed', 'closed' => 'bgc-green',
                    default => '',
                },
            ];
        };
    }

    public function can(Ability|string $ability): bool
    {
        return auth()->user()->isAdmin()
            || auth()->user()->isHavePermission(self::class, $ability);
    }
}
