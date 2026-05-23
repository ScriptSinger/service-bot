<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Manual\Pages;

use App\Models\Brand;
use App\Models\DeviceType;
use App\MoonShine\Resources\DeviceModel\DeviceModelResource;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Fields\ID;
use App\MoonShine\Resources\Manual\ManualResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Date;
use Throwable;


/**
 * @extends IndexPage<ManualResource>
 */
class ManualIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Brand', 'brand_name'),
            BelongsTo::make(
                'Device Model',
                'deviceModel',
                fn($item) => $item->name,
                DeviceModelResource::class
            )->sortable(),

            Date::make('Created At', 'created_at')->format('Y-m-d H:i:s')->sortable(),
            Date::make('Updated At', 'updated_at')->format('Y-m-d H:i:s')->sortable(),
        ];
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
            Select::make('Brand', 'brand_id')
                ->options(
                    Brand::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all()
                )
                ->nullable()
                ->searchable()
                ->onApply(static function ($query, $value) {
                    if (filled($value)) {
                        $query->whereHas(
                            'deviceModel',
                            static fn ($deviceModelQuery) => $deviceModelQuery->where('brand_id', $value)
                        );
                    }

                    return $query;
                }),
            Select::make('Тип техники', 'device_type_id')
                ->options(
                    DeviceType::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all()
                )
                ->nullable()
                ->searchable()
                ->onApply(static function ($query, $value) {
                    if (filled($value)) {
                        $query->whereHas(
                            'deviceModel.brand.deviceTypes',
                            static fn ($deviceTypeQuery) => $deviceTypeQuery->whereKey($value)
                        );
                    }

                    return $query;
                }),

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
