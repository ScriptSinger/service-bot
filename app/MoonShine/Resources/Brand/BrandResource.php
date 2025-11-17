<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Brand;

use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
use App\MoonShine\Resources\Brand\Pages\BrandIndexPage;
use App\MoonShine\Resources\Brand\Pages\BrandFormPage;
use App\MoonShine\Resources\Brand\Pages\BrandDetailPage;
use App\MoonShine\Resources\DeviceModel\DeviceModelResource;
use App\MoonShine\Resources\DeviceType\DeviceTypeResource;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Brand, BrandIndexPage, BrandFormPage, BrandDetailPage>
 */
class BrandResource extends ModelResource
{
    protected string $model = Brand::class;

    protected string $title = 'Brands';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Device Type', 'deviceType', DeviceTypeResource::class),
            Text::make('Name'),
            Text::make('Slug'),
            Text::make('Country'),
            HasMany::make('Models', 'deviceModels', DeviceModelResource::class),
        ];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            BrandIndexPage::class,
            BrandFormPage::class,
            BrandDetailPage::class,
        ];
    }
}
