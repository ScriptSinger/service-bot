<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Brand;

use App\Models\Brand;
use App\MoonShine\Resources\Brand\Pages\BrandIndexPage;
use App\MoonShine\Resources\Brand\Pages\BrandFormPage;
use App\MoonShine\Resources\Brand\Pages\BrandDetailPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Textarea;

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
            ID::make(),
            Textarea::make('Name', 'name'),
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
