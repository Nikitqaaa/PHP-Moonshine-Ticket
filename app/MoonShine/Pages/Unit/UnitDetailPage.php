<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Unit;

use Throwable;
use App\Models\User;
use App\Models\Ticket;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use Illuminate\Database\Query\Builder;
use MoonShine\UI\Components\Layout\Box;
use App\MoonShine\Resources\UserResource;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\TicketResource;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\HasMany;

final class UnitDetailPage extends DetailPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Text::make('Отдел', 'name'),
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
        $resource = $this->getResource();
        $item = $resource->getCastedData();

        return [
            Box::make([
                ...$this->getDetailComponents($item),
            ]),
            Tabs::make([
                Tab::make('Сотрудники', [
                    HasMany::make('Сотрудники', 'users', resource: UserResource::class)
                        ->modifyBuilder(
                            fn (Builder|null $query) => ($query ?? User::query())
                                ->where('unit_ulid', $this->getResource()->getItem()->ulid))
                        ->searchable(false),
                ])->icon('users'),
                Tab::make('Заявки', [
                    HasMany::make('Заявки', 'tickets', resource: TicketResource::class)
                        ->modifyBuilder(
                            fn (Builder|null $query) => ($query ?? Ticket::query())
                                ->where('unit_ulid', $this->getResource()->getItem()->ulid)
                        )
                        ->searchable(false),
                ])->icon('ticket'),
            ]),
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
