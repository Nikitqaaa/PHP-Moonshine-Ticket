<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\Models\Ticket;
use MoonShine\UI\Components\Link;
use MoonShine\UI\Components\When;
use App\MoonShine\Pages\Dashboard;
use MoonShine\UI\Components\Alert;
use MoonShine\MenuManager\MenuItem;
use MoonShine\Laravel\Enums\Ability;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuDivider;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Session;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\UI\Components\Breadcrumbs;
use MoonShine\UI\Components\Layout\Flex;
use App\MoonShine\Resources\UnitResource;
use App\MoonShine\Resources\UserResource;
use MoonShine\UI\Components\Layout\Header;
use MoonShine\UI\Components\Layout\Layout;
use MoonShine\UI\Components\Layout\TopBar;
use App\MoonShine\Resources\ServerResource;
use App\MoonShine\Resources\TicketResource;
use MoonShine\Laravel\Components\Layout\Search;
use MoonShine\Laravel\Components\Layout\Locales;
use App\MoonShine\Resources\ArchiveTicketResource;
use App\MoonShine\Resources\ProblemCategoryResource;
use MoonShine\Laravel\Components\Layout\Notifications;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Resources\MoonShineUserRoleResource;

final class MoonShineLayout extends AppLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function getHeaderComponent(): Header
    {
        return Header::make([
            Alert::make(type: 'info')->content(
                fn () => (string)Flex::make([
                    'Вы вошли под ' . Context::get('user')?->name,

                    Link::make(route('admin.auth.fake.logout'), 'Выйти')
                        ->button()
                        ->canSee(fn () => Session::has('X-USER-ID'))
                        ->icon('arrow-right-on-rectangle')
                        ->style('background-color: #ec4176'),

                ])
            )
                ->canSee(fn () => Session::has('X-USER-ID')),

            Breadcrumbs::make($this->getPage()->getBreadcrumbs())->prepend($this->getHomeUrl(), icon: 'home'),
            Search::make(),
            When::make(
                fn (): bool => $this->isUseNotifications(),
                static fn (): array => [Notifications::make()],
            ),
            Locales::make(),
        ])
            ->style('flex-wrap: wrap;');
    }

    protected function menu(): array
    {
        return [
            MenuItem::make('Главная', Dashboard::class),

            $this->getTicketLink()
                ->canSee(fn () => auth()->user()->isAdmin()
                    || auth()->user()->isHavePermission(TicketResource::class, Ability::VIEW)),

            MenuItem::make('Категории проблем', ProblemCategoryResource::class)
                ->canSee(fn () => auth()->user()->isAdmin()
                    || auth()->user()->isHavePermission(ProblemCategoryResource::class, Ability::VIEW)),

            MenuItem::make('Отделы', UnitResource::class)
                ->canSee(fn () => auth()->user()->isAdmin()
                    || auth()->user()->isHavePermission(UnitResource::class, Ability::VIEW)),

            MenuItem::make('Архив', ArchiveTicketResource::class)
                ->canSee(fn () => auth()->user()->isAdmin()
                    || auth()->user()->isHavePermission(ArchiveTicketResource::class, Ability::VIEW)),

            MenuGroup::make(static fn () => __('moonshine::ui.resource.system'), [
                MenuItem::make(
                    static fn () => __('moonshine::ui.resource.admins_title'),
                    UserResource::class
                ),
                MenuItem::make(
                    static fn () => __('moonshine::ui.resource.role_title'),
                    MoonShineUserRoleResource::class
                ),
                MenuItem::make('Сервера', ServerResource::class),

            ])
                ->canSee(fn () => auth()->user()->isAdmin())
                ->icon('cog-8-tooth'),
            MenuDivider::make(),
            MenuItem::make('WIKI', 'https://wiki.provizor.tech/', blank: fn () => true)
                ->icon('information-circle'),
            //            MenuItem::make('ArchiveComments', ArchiveCommentResource::class),
        ];
    }

    private function getTicketLink(): MenuItem
    {
        $link = MenuItem::make('Заявки', TicketResource::class);
        if ($count = Ticket::whereHas('ticketStatus', fn ($q) => $q->where('slug', '=', 'new'))->count()) {
            return $link->badge($count);
        }

        return $link;
    }

    /**
     * @param  ColorManager  $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);
    }

    public function build(): Layout
    {
        return parent::build();
    }

    protected function getFooterCopyright(): string
    {
        return \sprintf(
            <<<'HTML'
                &copy; 2023-%d Made with ❤️ by
                            <a href="https://provizor.tech"
                                class="font-semibold text-primary hover:text-secondary"
                                target="_blank"
                            >
                                PROVIZOR
                            </a>
                HTML,
            now()->year,
        );
    }

    protected function getFooterMenu(): array
    {
        return [
            'https://git.rightside.ru/provizor-tech/ticket/-/releases' => 'Version: ' . $this->getComposerVersion(),
        ];
    }

    protected function getComposerVersion(): string
    {
        $composerFile = base_path('composer.json');
        $composerData = json_decode(file_get_contents($composerFile), true);

        return $composerData['version'];
    }

    protected function getLogo(bool $small = false): string
    {
        $logo = $small ? 'logo-small.svg' : 'logo.svg';

        return $this->getAssetManager()->getAsset(
            $this->getCore()->getConfig()->getLogo($small) ?? "vendor/moonshine/$logo",
        );
    }
}
