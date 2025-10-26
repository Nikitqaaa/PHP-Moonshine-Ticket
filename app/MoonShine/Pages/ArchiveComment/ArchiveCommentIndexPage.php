<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\ArchiveComment;

use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Str;
use MoonShine\UI\Fields\Text;
use App\MoonShine\Resources\UserResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;

class ArchiveCommentIndexPage extends IndexPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            BelongsTo::make('Пользователь', 'user', resource: UserResource::class),
            Text::make('Комментарий', 'comment', fn ($item) => Str::limit($item->comment, 400)),
            Text::make('Дата создания', 'created_at', fn ($item) => Carbon::parse($item->created_at)
                ->translatedFormat('d F Y H:i')),
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
