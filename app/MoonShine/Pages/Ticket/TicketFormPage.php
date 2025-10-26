<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Ticket;

use Throwable;
use App\Models\TicketStatus;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Hidden;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Textarea;
use MoonShine\UI\Components\Layout\Grid;
use App\MoonShine\Resources\UserResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Column;
use App\MoonShine\Resources\PriorityResource;
use MoonShine\Contracts\UI\ComponentContract;
use App\MoonShine\Resources\ProblemCategoryResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;

final class TicketFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Grid::make([
                Column::make([
                    Text::make('Улица', 'street'),
                ])->columnSpan(8),
                Column::make([
                    Text::make('Дом', 'building'),
                ])->columnSpan(2),
                Column::make([
                    Text::make('Квартира', 'flat'),
                ])->columnSpan(2),
            ]),
            Grid::make([
                Column::make([
                    Text::make('ФИО Абонента', 'name')])->columnSpan(6),
                Column::make([
                    Text::make('Телефон', 'phone')])->columnSpan(4),
                Column::make([
                    Text::make('ЛС', 'ls')])->columnSpan(2),
            ]),
            Hidden::make('Отдел', 'unit_ulid')
                ->default(fn ($item) => $item->problemCategory->unit_ulid),
            Text::make('Офис', 'office'),
            Hidden::make('Создатель', 'owner_id')
                ->setValue($this->getResource()->getItem()->owner_id ?? auth()->id()),
            Grid::make([
                Column::make([
                    BelongsTo::make('Категория проблемы', 'problemCategory', resource: ProblemCategoryResource::class),
                ])->columnSpan(4),
                Column::make([
                    BelongsTo::make('Приоритет', 'priority', resource: PriorityResource::class),
                ])->columnSpan(4),
                Column::make([
                    Select::make('Статус', 'ticket_status_ulid')
                        ->options(TicketStatus::pluck('name', 'ulid')->toArray())
                        ->canSee(fn () => $this->getResource()->getItem()),
                ])->columnSpan(4),
            ]),
            BelongsTo::make('Ответственный', 'responsible', resource: UserResource::class)
                ->canSee(fn () => $this->getResource()->getItem())->nullable(),
            Textarea::make('Описание', 'description')
                ->customAttributes([
                    'rows' => 4,
                ]),
        ];
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
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
}
