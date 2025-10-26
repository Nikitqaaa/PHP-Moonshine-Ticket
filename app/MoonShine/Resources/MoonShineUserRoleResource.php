<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;
use MoonShine\UI\Fields\ID;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Enums\Ability;
use MoonShine\Support\Attributes\Icon;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Models\MoonshineUserRole;

#[Icon('bookmark')]
/**
 * @extends ModelResource<MoonshineUserRole>
 */
final class MoonShineUserRoleResource extends ModelResource
{
    protected string $model = MoonshineUserRole::class;

    protected string $column = 'name';

    protected bool $createInModal = true;

    protected bool $detailInModal = true;

    protected bool $editInModal = true;

    protected bool $cursorPaginate = false;

    public function getTitle(): string
    {
        return __('moonshine::ui.resource.role');
    }

    protected function activeActions(): ListOf
    {
        return parent::activeActions()
            ->except(Action::MASS_DELETE, Action::VIEW);
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make(__('moonshine::ui.resource.role_name'), 'name'),
        ];
    }

    protected function detailFields(): iterable
    {
        return $this->indexFields();
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make()->sortable(),
                Text::make(__('moonshine::ui.resource.role_name'), 'name')
                    ->required(),
            ]),
        ];
    }

    /**
     * @return array{name: array|string}
     */
    protected function rules($item): array
    {
        return [
            'name' => ['required', 'min:5'],
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'name',
        ];
    }

    public function can(Ability|string $ability): bool
    {
        return auth()->user()->isAdmin();
    }
}
