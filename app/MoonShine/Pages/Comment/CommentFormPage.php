<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Comment;

use App\MoonShine\Resources\CommentResource;
use Throwable;
use MoonShine\UI\Fields\Hidden;
use MoonShine\UI\Fields\Textarea;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @method CommentResource getResource()
 */
final class CommentFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Textarea::make('Комментарий', 'comment')
                ->customAttributes([
                    'rows' => 10,
                ])
                ->required(),
            Hidden::make('Пользователь', 'user_id')
                ->setValue($this->getResource()->getItem()->user_id ?? auth()->id()),
            Hidden::make('Заявка', 'ticket_id')
                ->setValue($this->getResource()->getItem()?->ticket_id),
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
