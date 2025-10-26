<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Priority;
use MoonShine\Laravel\Enums\Ability;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Pages\Priority\PriorityFormPage;
use App\MoonShine\Pages\Priority\PriorityIndexPage;
use App\MoonShine\Pages\Priority\PriorityDetailPage;

/**
 * @extends ModelResource<Priority, PriorityIndexPage, PriorityFormPage, PriorityDetailPage>
 */
final class PriorityResource extends ModelResource
{
    protected string $model = Priority::class;

    protected string $title = 'Приоритет';

    protected string $column = 'name';

    /**
     * @return list<class-string<\MoonShine\Contracts\Core\PageContract>>
     */
    protected function pages(): array
    {
        return [
            PriorityIndexPage::class,
            PriorityFormPage::class,
            PriorityDetailPage::class,
        ];
    }

    /**
     * @param  Priority  $item
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
        return auth()->user()->isAdmin();
    }
}
