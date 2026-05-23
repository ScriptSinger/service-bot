<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Manual\Pages;

use App\MoonShine\Resources\DeviceModel\DeviceModelResource;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\Manual\ManualResource;
use App\MoonShine\Resources\ManualFile\ManualFileResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Date;
use Throwable;


/**
 * @extends DetailPage<ManualResource>
 */
class ManualDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make(
                'Device Model',
                'deviceModel',
                fn($item) => $item->name,
                DeviceModelResource::class
            ),
            HasMany::make('Files', 'files', null, ManualFileResource::class)
                ->fields([
                    ID::make()->sortable(),
                    Text::make('Title', 'file_name'),
                    File::make('Manual File', 'file_url')
                        ->disk('yandex')
                        ->dir('manuals/files')
                        ->allowedExtensions(['pdf'])
                        ->removable(),
                    Date::make('Created At', 'created_at')->format('Y-m-d H:i:s')->sortable(),
                    Date::make('Updated At', 'updated_at')->format('Y-m-d H:i:s')->sortable(),
                ]),
            Date::make('Created At', 'created_at')->format('Y-m-d H:i:s'),
            Date::make('Updated At', 'updated_at')->format('Y-m-d H:i:s'),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @param  TableBuilder  $component
     *
     * @return TableBuilder
     */
    protected function modifyDetailComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
