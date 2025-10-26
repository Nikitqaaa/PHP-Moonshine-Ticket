<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Unit;
use MoonShine\Laravel\Enums\Ability;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Support\Enums\PageType;
use MoonShine\Support\Attributes\Icon;
use App\MoonShine\Pages\Unit\UnitFormPage;
use App\MoonShine\Pages\Unit\UnitIndexPage;
use App\MoonShine\Pages\Unit\UnitDetailPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\ListOf;

#[Icon('user-group')]
/**
 * @extends ModelResource<Unit, UnitIndexPage, UnitFormPage, UnitDetailPage>
 */
final class UnitResource extends ModelResource
{
    protected string $model = Unit::class;

    protected string $title = 'Отделы';

    protected string $column = 'name';

    protected PageType|null $redirectAfterSave = PageType::INDEX;

    protected bool $editInModal = true;

    /**
     * @return list<class-string<\MoonShine\Contracts\Core\PageContract>>
     */
    protected function pages(): array
    {
        return [
            UnitIndexPage::class,
            UnitFormPage::class,
            UnitDetailPage::class,
        ];
    }

    protected function search(): array
    {
        return ['name'];
    }

    /**
     * @param  Unit  $item
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
