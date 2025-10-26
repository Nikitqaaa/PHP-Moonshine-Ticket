<?php

declare(strict_types=1);

namespace App\Providers;

use MoonShine\AssetManager\Css;
use Illuminate\Support\ServiceProvider;
use App\MoonShine\Resources\UnitResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\ServerResource;
use App\MoonShine\Resources\TicketResource;
use App\MoonShine\Resources\CommentResource;
use App\MoonShine\Resources\PriorityResource;
use App\MoonShine\Resources\ArchiveTicketResource;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\ProblemCategoryResource;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use MoonShine\Contracts\AssetManager\AssetManagerContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     */
    public function boot(CoreContract $core, ConfiguratorContract $config, AssetManagerContract $assets): void
    {
        //         $config->authEnable();

        $assets->add(Css::make('/admin.css'));

        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                TicketResource::class,
                ProblemCategoryResource::class,
                UnitResource::class,
                UserResource::class,
                PriorityResource::class,
                CommentResource::class,
                ArchiveTicketResource::class,
                ServerResource::class,
                //                ArchiveCommentResource::class,
            ])
            ->pages([
                ...$config->getPages(),
            ]);
    }
}
