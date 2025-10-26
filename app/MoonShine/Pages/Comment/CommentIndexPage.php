<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Comment;

use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Str;
use MoonShine\UI\Fields\Text;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;

final class CommentIndexPage extends IndexPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Text::make('Пользователь', 'user.name'),
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
