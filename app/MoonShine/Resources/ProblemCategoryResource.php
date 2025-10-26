<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\ProblemCategory;
use MoonShine\Laravel\Enums\Ability;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Support\Enums\PageType;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Pages\ProblemCategory\ProblemCategoryFormPage;
use App\MoonShine\Pages\ProblemCategory\ProblemCategoryIndexPage;
use App\MoonShine\Pages\ProblemCategory\ProblemCategoryDetailPage;
use MoonShine\Support\ListOf;

#[Icon('rectangle-group')]
/**
 * @extends ModelResource<ProblemCategory, ProblemCategoryIndexPage, ProblemCategoryFormPage, ProblemCategoryDetailPage>
 */
final class ProblemCategoryResource extends ModelResource
{
    protected string $model = ProblemCategory::class;

    protected string $column = 'name';

    protected string $title = 'Категории проблем';

    protected PageType|null $redirectAfterSave = PageType::INDEX;

    protected bool $editInModal = true;

    /**
     * @return list<class-string<\MoonShine\Contracts\Core\PageContract>>
     */
    protected function pages(): array
    {
        return [
            ProblemCategoryIndexPage::class,
            ProblemCategoryFormPage::class,
            //            ProblemCategoryDetailPage::class,
        ];
    }

    /**
     * @param  ProblemCategory  $item
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
            ->except(Action::MASS_DELETE);
    }
}
