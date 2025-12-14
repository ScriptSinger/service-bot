<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DeviceModel;

use App\Models\DeviceModel;
use App\MoonShine\Resources\DeviceModel\Pages\DeviceModelIndexPage;
use App\MoonShine\Resources\DeviceModel\Pages\DeviceModelFormPage;
use App\MoonShine\Resources\DeviceModel\Pages\DeviceModelDetailPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<DeviceModel, DeviceModelIndexPage, DeviceModelFormPage, DeviceModelDetailPage>
 */
class DeviceModelResource extends ModelResource
{
    protected string $model = DeviceModel::class;
    protected string $title = 'DeviceModels';

    public function fields(): array
    {
        return [];
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
