<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Unit;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Priority;
use App\Models\TicketStatus;
use App\Models\ProblemCategory;
use MoonShine\Laravel\Pages\Page;
use MoonShine\UI\Components\When;
use MoonShine\Support\Attributes\Icon;
use MoonShine\UI\Components\Layout\Grid;
use App\MoonShine\Resources\UnitResource;
use App\MoonShine\Resources\UserResource;
use MoonShine\UI\Components\Layout\Column;
use App\MoonShine\Resources\TicketResource;
use MoonShine\UI\Components\Layout\Divider;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Layout\LineBreak;
use App\MoonShine\Resources\ProblemCategoryResource;
use MoonShine\Apexcharts\Components\DonutChartMetric;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;

#[Icon('s.home')]
final class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle(),
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Главная';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        return [
            Grid::make([
                Column::make([
                    ValueMetric::make('Всего заявок')
                        ->value(fn () => Ticket::count()
                        )->icon('document-duplicate')
                        ->customAttributes([
                            'onclick' => "window.location.href='" . app(TicketResource::class)
                                ->getIndexPageUrl(['query-tag' => 'vse']) . "'",
                            'style' => 'cursor: pointer;',
                        ]),
                ])->columnSpan(2, 6),

                Column::make([
                    ValueMetric::make('Новых заявок')
                        ->value(fn () => Ticket::whereHas('ticketStatus',
                            fn ($query) => $query->where('slug', 'new'))->count()
                        )->icon('document-plus')
                        ->customAttributes([
                            'onclick' => "window.location.href='" . app(TicketResource::class)
                                ->getIndexPageUrl(['query-tag' => 'novye']) . "'",
                            'style' => 'cursor: pointer;',
                        ]),
                ])->columnSpan(2, 6),

                Column::make([
                    ValueMetric::make('Заявок в работе')
                        ->value(fn () => Ticket::whereHas('ticketStatus',
                            fn ($query) => $query->where('slug', 'in_progress'))->count()
                        )->icon('document-chart-bar')
                        ->customAttributes([
                            'onclick' => "window.location.href='" . app(TicketResource::class)
                                ->getIndexPageUrl(['query-tag' => 'v-rabote']) . "'",
                            'style' => 'cursor: pointer;',
                        ]),
                ])->columnSpan(2, 6),

                Column::make([
                    ValueMetric::make('Выполненых заявок')
                        ->value(fn () => Ticket::whereHas('ticketStatus',
                            fn ($query) => $query->where('slug', 'completed'))->count()
                        )->icon('document-check')
                        ->customAttributes([
                            'onclick' => "window.location.href='" . app(TicketResource::class)
                                ->getIndexPageUrl(['query-tag' => 'vypolnenye']) . "'",
                            'style' => 'cursor: pointer;',
                        ]),
                ])->columnSpan(2, 6),

                Column::make([
                    ValueMetric::make('Закрытых заявок')
                        ->value(fn () => Ticket::whereHas('ticketStatus',
                            fn ($query) => $query->where('slug', 'closed'))->count()
                        )->icon('document-text')
                        ->customAttributes([
                            'onclick' => "window.location.href='" . app(TicketResource::class)
                                ->getIndexPageUrl(['query-tag' => 'zakrytye']) . "'",
                            'style' => 'cursor: pointer;',
                        ]),
                ])->columnSpan(2, 6),

                Column::make([
                    ValueMetric::make('Без ответственного')
                        ->value(fn () => Ticket::whereNull('responsible_id')->count()
                        )->icon('user-minus')
                        ->customAttributes([
                            'onclick' => "window.location.href='" . app(TicketResource::class)
                                ->getIndexPageUrl(['query-tag' => 'bez-otvetstvennogo']) . "'",
                            'style' => 'cursor: pointer;',
                        ]),
                ])->columnSpan(2, 6),

                //                Column::make([
                //                    ValueMetric::make('Не срочные')
                //                        ->value(fn() => Ticket::whereNull('deadline_at')->count())
                //                    ->icon('chevron-double-down')
                //                    ->customAttributes([
                //                        'onclick' => "window.location.href='" . app(TicketResource::class)
                //                        ->getIndexPageUrl(['query-tag' => 'ne-srocnye']) . "'",
                //                        'style' => 'cursor: pointer;'
                //                    ])
                //                ])->columnSpan(3, 6),

                When::make(
                    fn () => auth()->user()->moonshine_user_role_id === 1,
                    fn () => [
                        Column::make([
                            ValueMetric::make('Количество пользователей')
                                ->value(fn () => User::count()
                                )->icon('users')
                                ->customAttributes([
                                    'onclick' => "window.location.href='" . app(UserResource::class)
                                        ->getIndexPageUrl() . "'",
                                    'style' => 'cursor: pointer;',
                                ]),
                        ])->columnSpan(4, 6),

                        Column::make([
                            ValueMetric::make('Количество отделов')
                                ->value(fn () => Unit::count()
                                )->icon('user-group')
                                ->customAttributes([
                                    'onclick' => "window.location.href='" . app(UnitResource::class)
                                        ->getIndexPageUrl() . "'",
                                    'style' => 'cursor: pointer;',
                                ]),
                        ])->columnSpan(4, 6),

                        Column::make([
                            ValueMetric::make('Категории проблем')
                                ->value(fn () => ProblemCategory::count()
                                )->icon('rectangle-group')
                                ->customAttributes([
                                    'onclick' => "window.location.href='" . app(ProblemCategoryResource::class)
                                        ->getIndexPageUrl() . "'",
                                    'style' => 'cursor: pointer;',
                                ]),
                        ])->columnSpan(4, 6),
                    ]
                ), LineBreak::make(),
            ],

            ),

            Divider::make('Диаграммы'),

            Grid::make([
                DonutChartMetric::make('Заявки')
                    ->values(
                        TicketStatus::pluck('name')->mapWithKeys(
                            fn ($status) => [$status => Ticket::whereHas('ticketStatus',
                                fn ($query) => $query->where('name', $status)
                            )->count()]
                        )->toArray()
                    )->columnSpan(6, 12),

                DonutChartMetric::make('Приоритет заявок')
                    ->values(
                        Priority::pluck('name')->mapWithKeys(
                            fn ($priority) => [$priority => Ticket::whereHas('priority',
                                fn ($query) => $query->where('name', $priority)
                            )->count()]
                        )->toArray()
                    )->columnSpan(6, 12),
            ]),
        ];
    }
}
