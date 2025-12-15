<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DeviceModel;

use App\Models\DeviceModel;
use App\MoonShine\Resources\Brand\BrandResource;
use App\MoonShine\Resources\DeviceModel\Pages\DeviceModelIndexPage;
use App\MoonShine\Resources\DeviceModel\Pages\DeviceModelFormPage;
use App\MoonShine\Resources\DeviceModel\Pages\DeviceModelDetailPage;
use App\MoonShine\Resources\DeviceType\DeviceTypeResource;
use App\MoonShine\Resources\Manual\ManualResource;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<DeviceModel, DeviceModelIndexPage, DeviceModelFormPage, DeviceModelDetailPage>
 */
class DeviceModelResource extends ModelResource
{
    protected string $model = DeviceModel::class;
    protected string $title = 'DeviceModels';

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(
                'Тип техники',
                'deviceType',
                fn($item) => $item->name,
                DeviceTypeResource::class
            )->sortable(),

            BelongsTo::make(
                'Brand',
                'brand',
                fn($item) => $item->name,
                BrandResource::class
            )->sortable(),
            Text::make('Name')->sortable(),
            Textarea::make('Description'),
            Number::make('Year From', 'year_from')->sortable(),
            Number::make('Year To', 'year_to')->sortable(),
            Text::make('Image URL', 'image_url')->sortable(),
            Switcher::make('Active'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                BelongsTo::make(
                    'Тип техники',
                    'deviceType',
                    fn($item) => $item->name,
                    DeviceTypeResource::class
                )->required()
                    ->nullable(),

                BelongsTo::make(
                    'Brand',
                    'brand',
                    fn($item) => $item->name,
                    BrandResource::class
                ),
                Text::make('Name'),
                Textarea::make('Description'),
                Number::make('Year From', 'year_from'),
                Number::make('Year To', 'year_to'),
                Text::make('Image URL', 'image_url'),
                Switcher::make('Active'),

            ]),
        ];
    }

    protected function detailFields(): iterable
    {
        return [

            ID::make(),
            BelongsTo::make(
                'Тип техники',
                'deviceType',
                fn($item) => $item->name,
                DeviceTypeResource::class
            ),
            BelongsTo::make(
                'Brand',
                'brand',
                fn($item) => $item->name,
                BrandResource::class
            ),
            Text::make('Name'),
            Textarea::make('Description'),
            Number::make('Year From', 'year_from'),
            Number::make('Year To', 'year_to'),
            Text::make('Image URL', 'image_url'),
            Switcher::make('Active'),
            HasMany::make('Manuals', 'manuals', ManualResource::class),
            // HasMany::make('Test Modes', 'testModes',  TestModeResource::class),
            // HasMany::make('Error Codes', 'errorCodes', ErrorCodeResource::class),
        ];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            DeviceModelIndexPage::class,
            DeviceModelFormPage::class,
            DeviceModelDetailPage::class,
        ];
    }

    public function search(): array
    {
        return [
            'name',
        ];
    }
}
