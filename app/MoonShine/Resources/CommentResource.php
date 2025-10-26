<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Comment;
use MoonShine\Support\ListOf;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Enums\Ability;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Pages\Comment\CommentFormPage;
use App\MoonShine\Pages\Comment\CommentIndexPage;
use App\MoonShine\Pages\Comment\CommentDetailPage;

/**
 * @extends ModelResource<Comment, CommentIndexPage, CommentFormPage, CommentDetailPage>
 *
 * @method Comment|null getItem()
 */
final class CommentResource extends ModelResource
{
    protected string $model = Comment::class;

    protected string $title = 'Comments';

    protected bool $detailInModal = true;

    /**
     * @return list<class-string<\MoonShine\Contracts\Core\PageContract>>
     */
    protected function pages(): array
    {
        return [
            CommentIndexPage::class,
            CommentFormPage::class,
            CommentDetailPage::class,
        ];
    }

    /**
     * @param  Comment  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

    public function can(Ability|string $ability): bool
    {
        return auth()->user()->isAdmin()
            || auth()->user()->isHavePermission(self::class, $ability);
    }

    protected function activeActions(): ListOf
    {
        return parent::activeActions()
            ->except(Action::DELETE, Action::MASS_DELETE, Action::UPDATE);
    }
}
