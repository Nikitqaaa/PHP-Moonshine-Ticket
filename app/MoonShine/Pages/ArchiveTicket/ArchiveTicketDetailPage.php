<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\ArchiveTicket;

use Throwable;
use MoonShine\UI\Fields\Text;
use MoonShine\Support\Enums\Color;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;

final class ArchiveTicketDetailPage extends DetailPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Text::make('Заявка', 'title'),
            Text::make('Приоритет', 'priority.name')
                ->badge(fn ($item) => match ($item->priority->slug) {
                    'critical'    => Color::ERROR,
                    'high'        => Color::SECONDARY,
                    'medium'      => Color::INFO,
                    'low'         => Color::GRAY,
                    'enhancement' => Color::PRIMARY,
                    default       => '',
                }),
            Text::make('Статус', 'ticketStatus.name'),
            Text::make('Категория проблемы', 'problemCategory.name'),
            Text::make('Ответственный', 'responsible.name'),
            Text::make('Создатель', 'owner.name'),
            Text::make('Описание', 'description'),

            //            HasMany::make('Коментарии', 'archiveComments', resource: ArchiveCommentResource::class)
            //                ->creatable(false)->searchable(false),
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
