<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Ticket;

use Throwable;
use Carbon\Carbon;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\Support\Enums\Color;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\TicketResource;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @method TicketResource getResource()
 */
final class TicketIndexPage extends IndexPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make('Номер заявки', 'id')->sortable(),
            Text::make('Офис', 'office')->sortable(),
            Text::make('Дата создания', 'created_at', fn ($item) => Carbon::parse($item->created_at)
                ->translatedFormat('d F Y H:i'))->sortable(),
            Text::make('Статус', 'ticketStatus.name'),
            Text::make('Адрес', 'full_address'),
            Text::make('Категория проблемы', 'problemCategory.name'),
            Text::make('Приоритет', 'priority.name')
                ->badge(function () {
                    if (in_array($this->getResource()->getItem()?->ticketStatus?->slug, ['closed', 'completed'], true)) {
                        return '';
                    }

                    $slug = $this->getResource()->getItem()?->priority?->slug;

                    return match ($slug) {
                        'critical'    => Color::ERROR,
                        'high'        => Color::SECONDARY,
                        'medium'      => Color::INFO,
                        'low'         => Color::GRAY,
                        'enhancement' => Color::PRIMARY,
                        default       => '',
                    };
                }),
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
