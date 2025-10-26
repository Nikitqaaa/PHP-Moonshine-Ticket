<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Server;

use Throwable;
use Illuminate\Support\Str;
use MoonShine\UI\Fields\Text;
use Laravel\Sanctum\HasApiTokens;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Flash;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Layout\LineBreak;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Table\TableBuilder;

/**
 * @extends DetailPage<ModelResource>
 */
class ServerDetailPage extends DetailPage
{
    use HasApiTokens;

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Text::make('Ulid', 'ulid'),
            Text::make('Наименование', 'name'),
            Text::make('Дата создания', 'created_at'),
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
            Flash::make('create', 'success', removable: false),
            ...parent::mainLayer(),
            LineBreak::make(),
            Flex::make([
                ActionButton::make(
                    'Создать новый токен',
                    route('admin.server.create-token', ['server' => $this->getResource()->getItemID()]),
                )
                    ->withAttributes(['class' => 'my-5']),
                ActionButton::make(
                    'Удалить все токены',
                    fn ($item) => route(
                        'admin.server.delete-all-token',
                        ['server' => $this->getResource()->getItemID()]
                    )
                )
                    ->withConfirm('Подтвердить', 'Вы удалите все токены, PROVIZOR не сможет подключиться')
                    ->withAttributes(['class' => 'my-5 btn-drop-tokens']),
            ])->justifyAlign('start'),

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
            Grid::make([
                Column::make([
                    Tabs::make([
                        $this->tabList(),
                    ]),
                ]),
            ])->withAttributes(['class' => 'my-5']),
        ];
    }

    private function tabList(): Tab
    {
        return Tab::make(
            'Список Токенов',
            [TableBuilder::make()
                ->fields([
                    Text::make('Токен авторизации', 'token'),
                ])
                ->buttons([
                    ActionButton::make('Удалить', fn ($item) => route(
                        'admin.server.delete-token',
                        ['server' => $this->getResource()->getItemID(), 'token_id' => $item['id']]
                    ))
                        ->withConfirm('Уверены', 'После удаления токена, СКУД не сможет подключиться')
                        ->icon('trash'),
                ])
                ->items($this->getResource()
                    ->getItem()
                    ->tokens()
                    ->get()
                    ->makeVisible(['token'])
                    ->map(function ($item) {
                        $item->token = Str::limit($item->token, 10, '****');

                        return $item;
                    })
                    ->toArray()),
            ]
        );
    }
}
