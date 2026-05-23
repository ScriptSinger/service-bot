<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DeviceModel\Pages;

use App\Models\Brand;
use App\Models\DeviceType;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use App\MoonShine\Resources\DeviceModel\DeviceModelResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use Throwable;

/**
 * @extends IndexPage<DeviceModelResource>
 */
class DeviceModelIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @return list<FieldContract>
     */
    protected function filters(): iterable
    {
        return [
            Text::make('Name', 'name'),
            Select::make('Тип техники', 'device_type_id')
                ->options(
                    DeviceType::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all()
                )
                ->nullable()
                ->searchable(),
            Select::make('Brand', 'brand_id')
                ->options(
                    Brand::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all()
                )
                ->nullable()
                ->searchable(),
            Select::make('Active', 'active')
                ->options([
                    1 => 'Yes',
                    0 => 'No',
                ])
                ->nullable(),
        ];
    }

    /**
     * @return list<QueryTag>
     */
    protected function queryTags(): array
    {
        return [];
    }

    /**
     * @return list<Metric>
     */
    protected function metrics(): array
    {
        return [];
    }

    /**
     * @param  TableBuilder  $component
     *
     * @return TableBuilder
     */
    protected function modifyListComponent(ComponentContract $component): ComponentContract
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
