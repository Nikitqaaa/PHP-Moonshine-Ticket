<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Server;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Support\Enums\PageType;
use MoonShine\Support\Attributes\Icon;
use App\MoonShine\Pages\Server\ServerFormPage;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Pages\Server\ServerIndexPage;
use App\MoonShine\Pages\Server\ServerDetailPage;

#[Icon('server')]
/**
 * @extends ModelResource<Server, ServerIndexPage, ServerFormPage, ServerDetailPage>
 */
class ServerResource extends ModelResource
{
    protected PageType|null $redirectAfterSave = PageType::INDEX;

    protected string $model = Server::class;

    protected string $title = 'Сервера';

    protected int $itemsPerPage = 25;

    protected bool $isAsync = true;

    protected bool $createInModal = true;

    protected bool $editInModal = true;

    /**
     * @return list<class-string<Page>>
     */
    protected function pages(): array
    {
        return [
            ServerIndexPage::class,
            ServerFormPage::class,
            ServerDetailPage::class,
        ];
    }

    /**
     * @param  Server  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
