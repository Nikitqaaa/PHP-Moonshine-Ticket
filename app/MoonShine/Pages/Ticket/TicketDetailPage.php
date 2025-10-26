<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Ticket;

use Throwable;
use Carbon\Carbon;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Textarea;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Components\Layout\Column;
use App\MoonShine\Resources\TicketResource;
use App\MoonShine\Resources\CommentResource;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\HasMany;

/**
 * @method TicketResource getResource()
 */
final class TicketDetailPage extends DetailPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            HasMany::make('Комментарии', 'comments', resource: CommentResource::class)
                ->creatable(fn () => !in_array($this->getResource()->getItem()?->ticketStatus?->slug, ['closed', 'completed']))
                ->searchable(false),
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
            Box::make([
                Grid::make([
                    Column::make([
                        Text::make('Номер заявки')->setValue($this->getResource()->getItem()->id)->readonly(),
                    ], 3),
                    Column::make([
                        Text::make('Офис')->setValue($this->getResource()->getItem()->office)->readonly(),
                    ], 3),
                    Column::make([
                        Text::make('Адрес')->setValue($this->getResource()->getItem()->full_address)->readonly(),
                    ], 3),
                    Column::make([
                        Text::make('Дата создания')->setValue(Carbon::parse($this->getResource()->getItem()->created_at)
                            ->translatedFormat('d F Y H:i'))->readonly(),
                    ], 3),
                ]),
                Grid::make([
                    Column::make([
                        Text::make('ФИО')->setValue($this->getResource()->getItem()->name)->readonly(),
                    ], 6),
                    Column::make([
                        Text::make('Лицевой счет')->setValue($this->getResource()->getItem()->ls)->readonly(),
                    ], 3),
                    Column::make([
                        Phone::make('Номер телефона')->setValue($this->getResource()->getItem()->phone)->readonly(),
                    ], 3),
                ]),
                Grid::make([
                    Column::make([
                        Text::make('Категория проблемы')->setValue($this->getResource()->getItem()->problemCategory->name)->readonly(),
                    ], 2),
                    Column::make([
                        Text::make('Приоритет')->setValue($this->getResource()->getItem()->priority->name)->readonly(),
                    ], 2),
                    Column::make([
                        Text::make('Статус')->setValue($this->getResource()->getItem()->ticketStatus->name)->readonly(),
                    ], 2),
                    Column::make([
                        Text::make('Ответственный')->setValue($this->getResource()->getItem()->responsible?->name)->readonly(),
                    ], 3),
                    Column::make([
                        Text::make('Создатель')->setValue($this->getResource()->getItem()->owner->name)->readonly(),
                    ], 3),
                ]),
                Textarea::make('Описание')->setValue($this->getResource()->getItem()->description)->customAttributes([
                    'rows' => 6,
                ])->readonly(),
            ]),
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
