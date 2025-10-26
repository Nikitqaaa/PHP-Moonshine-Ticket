<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\ArchiveTicket;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Priority;
use MoonShine\UI\Fields\ID;
use App\Models\ArchiveTable;
use MoonShine\UI\Fields\Text;
use App\Models\ProblemCategory;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Components\Link;
use MoonShine\Support\Enums\Color;
use MoonShine\UI\Components\Badge;
use MoonShine\UI\Fields\DateRange;
use MoonShine\UI\Components\Collapse;
use MoonShine\Support\Enums\FormMethod;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;

final class ArchiveTicketIndexPage extends IndexPage
{
    protected function fields(): iterable
    {
        return [
            ID::make('Номер заявки', 'id')->sortable(),
            Text::make('Дата создания', 'created_at', fn ($item) => Carbon::parse($item->created_at)
                ->translatedFormat('d F Y H:i'))->sortable(),
            Text::make('Статус', 'ticketStatus.name'),
            Text::make('Адрес', 'full_address'),
            Text::make('Категория проблемы', 'problemCategory.name'),
            Text::make('Приоритет', 'priority.name'),
            Text::make('Ответственный', 'responsible.name'),
        ];
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function topLayer(): array
    {

        $activeFilters = collect([
            'Год'           => request('year'),
            'Приоритет'     => request('priority_ulid') ? Priority::find(request('priority_ulid'))?->name : null,
            'Категория'     => request('problem_category_ulid') ? ProblemCategory::find(request('problem_category_ulid'))?->name : null,
            'Ответственный' => request('responsible_id') ? User::find(request('responsible_id'))?->name : null,
            'Дата от'       => request('created_at')['from'] ?? null,
            'Дата до'       => request('created_at')['to'] ?? null,
        ])->filter();

        $filterBadges = $activeFilters->map(function ($value, $key) {
            $query = collect(request()->query());
            $paramKey = null;

            if (str_starts_with($key, 'Дата')) {
                $query = $query->forget('created_at');
            } else {
                $paramKey = match ($key) {
                    'Год'           => 'year',
                    'Приоритет'     => 'priority_ulid',
                    'Категория'     => 'problem_category_ulid',
                    'Ответственный' => 'responsible_id',
                    default         => null,
                };

                if ($paramKey) {
                    $query = $query->forget($paramKey);
                }
            }

            $url = route('moonshine.resource.page', array_merge([
                'resourceUri' => 'archive-ticket-resource',
                'pageUri'     => 'archive-ticket-index-page',
            ], $query->all()));

            $button = $paramKey === 'year' ? '' : Link::make($url)
                ->icon('x-mark');

            return Badge::make("<span class='title'>$key: </span> $value  $button", Color::INFO)->setAttribute('class', 'chip-btn');
        });

        return [
            Box::make([
                Grid::make([
                    Column::make($filterBadges->values()->all())->columnSpan(12),
                ]),
                Collapse::make('Архив', [
                    FormBuilder::make()
                        ->method(FormMethod::GET)
                        ->action(route('admin.archive.view.switch'))
                        ->fields([
                            Select::make('Год архива', 'year')
                                ->options(fn () => ArchiveTable::query()
                                    ->where('table_original', 'tickets')
                                    ->get()
                                    ->mapWithKeys(fn ($row) => [date('Y', $row->start_at) => date('Y', $row->start_at)])
                                    ->unique()
                                    ->toArray()
                                )
                                ->required()
                                ->default(request('year') ?? session('archive_year')),
                            Grid::make([
                                Column::make([
                                    Select::make('Категория проблемы', 'problem_category_ulid')
                                        ->options(fn () => ProblemCategory::pluck('name', 'ulid')->toArray())
                                        ->nullable()
                                        ->searchable()
                                        ->default(request('problem_category_ulid')),
                                ])->columnSpan(3),
                                Column::make([
                                    Select::make('Ответственный', 'responsible_id')
                                        ->options(fn () => User::pluck('name', 'id')->toArray())
                                        ->nullable()
                                        ->searchable()
                                        ->default(request('responsible_id')),
                                ])->columnSpan(3),
                                Column::make([
                                    Select::make('Приоритет', 'priority_ulid')
                                        ->options(fn () => Priority::pluck('name', 'ulid')->toArray())
                                        ->nullable()
                                        ->searchable()
                                        ->default(request('priority_ulid')),
                                ])->columnSpan(3),
                                Column::make([
                                    DateRange::make('Дата создания', 'created_at')
                                        ->nullable()
                                        ->reactive()
                                        ->setValue([
                                            'from' => request()->collect('created_at')->get('from'),
                                            'to'   => request()->collect('created_at')->get('to'),
                                        ])
                                        ->min(Carbon::createFromDate((int)request('year'), 1, 01)->format('Y-m-d'))
                                        ->max(Carbon::createFromDate((int)request('year'), 12, 31)->format('Y-m-d')),
                                ])->columnSpan(3),
                            ]),
                        ])->submit('Применить фильтры')
                        ->buttons([
                            ActionButton::make('Сбросить фильтры')
                                ->customAttributes([
                                    'href' => route('moonshine.resource.page', [
                                        'resourceUri' => 'archive-ticket-resource',
                                        'pageUri'     => 'archive-ticket-index-page',
                                    ]),
                                ]),
                        ]),
                ]), ]),
        ];
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
        ];
    }

    protected function search(): array
    {
        return [
            'name',
            'unit_ulid',
            'category_ulid',
            'created_at',
            'status_ulid',
        ];
    }
}
