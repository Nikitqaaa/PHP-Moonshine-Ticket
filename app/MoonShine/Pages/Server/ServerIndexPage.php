<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Server;

use Throwable;
use App\Models\Server;
use MoonShine\UI\Fields\Text;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends IndexPage<ModelResource>
 */
class ServerIndexPage extends IndexPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Text::make('Ulid', 'ulid'),
            Text::make('Наименование сервера', 'name'),
            Text::make('Время последнего запроса', 'last_used_at',
                fn ($q) => (Server::find($q->ulid)->tokens()->orderByDesc('last_used_at')->first()?->last_used_at?->format('d.m.Y H:i:s')),
            )
                ->readonly(),
            Text::make('Дата создания', 'created_at'),
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
