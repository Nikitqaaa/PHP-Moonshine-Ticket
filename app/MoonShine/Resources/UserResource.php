<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Carbon\Carbon;
use App\Models\User;
use MoonShine\Laravel\Enums\Action;
use MoonShine\UI\Fields\ID;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Hidden;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Fields\Password;
use MoonShine\Support\Enums\Color;
use Illuminate\Support\Facades\Auth;
use MoonShine\Laravel\Enums\Ability;
use MoonShine\Support\Enums\PageType;
use MoonShine\UI\Components\Collapse;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\Support\Attributes\Icon;
use Illuminate\Support\Facades\Session;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\PasswordRepeat;
use MoonShine\UI\Components\ActionButton;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Models\MoonshineUserRole;
use MoonShine\Permissions\Traits\WithPermissions;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
#[Icon('users')]
/**
 * @extends ModelResource<User>
 */
final class UserResource extends MoonShineUserResource
{
    use WithPermissions;

    protected string $model = User::class;

    protected string $title = 'Пользователи';

    protected int $itemsPerPage = 25;

    protected bool $simplePaginate = false;

    protected PageType|null $redirectAfterSave = PageType::INDEX;

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),

            Text::make(__('moonshine::ui.resource.name'), 'name'),

            Text::make('Номер телефона', 'phone'),
            Text::make('Отдел', 'unit.name'),
            Text::make('Должность', 'position'),
            Text::make(
                __('moonshine::ui.resource.role'),
                'moonshineUserRole.name'
            )->badge(fn () => match ($this->getItem()->moonshine_user_role_id) {
                1       => Color::SECONDARY,
                2       => Color::PRIMARY,
                default => '',
            }),

            //            Image::make(__('moonshine::ui.resource.avatar'), 'avatar')->modifyRawValue(fn (
            //                string|null $raw
            //            ): string => $raw ?? ''),

            Text::make(
                __('moonshine::ui.resource.created_at'),
                'created_at',
                fn ($item) => Carbon::parse($item->created_at)->translatedFormat('d F Y')
            ),

            Email::make(__('moonshine::ui.resource.email'), 'email'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                Tabs::make([
                    Tab::make(__('moonshine::ui.resource.main_information'), [
                        ID::make()->sortable(),

                        Text::make(__('moonshine::ui.resource.name'), 'name')
                            ->required(),

                        Email::make(__('moonshine::ui.resource.email'), 'email')
                            ->required(),

                        Phone::make('Номер телефона', 'phone')
                            ->required(),

                        BelongsTo::make(
                            'Роль',
                            'moonshineUserRole',
                            formatted: static fn (MoonshineUserRole $model) => $model->name,
                            resource: MoonShineUserRoleResource::class,
                        )
                            ->reactive()
                            ->valuesQuery(static fn (Builder $q) => $q->select(['id', 'name']))
                            ->required(),

                        BelongsTo::make('Отдел', 'unit', resource: UnitResource::class)
                            ->required(),

                        Text::make('Должность', 'position')
                            ->required(),

                        //                        Image::make(__('moonshine::ui.resource.avatar'), 'avatar')
                        //                            ->disk(moonshineConfig()->getDisk())
                        //                            ->dir('moonshine_users')
                        //                            ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif']),

                        Hidden::make(__('moonshine::ui.resource.created_at'), 'created_at')
                            ->default(now()->toDateTimeString()),
                    ])->icon('user-circle'),

                    Tab::make(__('moonshine::ui.resource.password'), [
                        Collapse::make(__('moonshine::ui.resource.change_password'), [
                            Password::make(__('moonshine::ui.resource.password'), 'password')
                                ->customAttributes(['autocomplete' => 'new-password'])
                                ->eye(),

                            PasswordRepeat::make(__('moonshine::ui.resource.repeat_password'), 'password_repeat')
                                ->customAttributes(['autocomplete' => 'confirm-password'])
                                ->eye(),
                        ])->icon('lock-closed'),
                    ])->icon('lock-closed'),
                ]),
            ]),
        ];
    }

    protected function search(): array
    {
        return ['name', 'moonshine_user_role'];
    }

    /**
     * @param  User  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

    protected function detailButtons(): ListOf
    {
        $buttons = parent::detailButtons();

        /** @var User $user */
        $user = $this->getItem();

        if (auth()->user()->isAdmin() && auth()->id() !== $user->id) {
            $buttons->add(
                ActionButton::make('Войти от имени')
                    ->method('auth')
                    ->icon('user-circle')
            );
        }

        return $buttons;
    }

    public function auth()
    {
        /** @var \App\Models\User $item */
        $item = $this->getItem();
        $user = User::find($item->id);

        if ($user) {
            Session::put('ORIGINAL-USER-ID', auth()->id());
            Session::put('X-USER-ID', (string)$user->id);
            Auth::login($user);
        }

        return redirect('/');
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
