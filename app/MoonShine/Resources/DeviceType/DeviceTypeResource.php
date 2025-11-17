<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DeviceType;

use Illuminate\Database\Eloquent\Model;
use App\Models\DeviceType;
use App\MoonShine\Resources\Brand\BrandResource;
use App\MoonShine\Resources\DeviceType\Pages\DeviceTypeIndexPage;
use App\MoonShine\Resources\DeviceType\Pages\DeviceTypeFormPage;
use App\MoonShine\Resources\DeviceType\Pages\DeviceTypeDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<DeviceType, DeviceTypeIndexPage, DeviceTypeFormPage, DeviceTypeDetailPage>
 */
class DeviceTypeResource extends ModelResource
{
    protected string $model = DeviceType::class;

    protected string $title = 'DeviceTypes';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name'),
            Text::make('Slug'),
            HasMany::make('Brands', 'brands', BrandResource::class),
        ];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            DeviceTypeIndexPage::class,
            DeviceTypeFormPage::class,
            DeviceTypeDetailPage::class,
        ];
    }
}
