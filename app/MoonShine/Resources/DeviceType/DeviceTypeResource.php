<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DeviceType;

use App\Models\DeviceType;
use App\MoonShine\Resources\DeviceType\Pages\DeviceTypeIndexPage;
use App\MoonShine\Resources\DeviceType\Pages\DeviceTypeFormPage;
use App\MoonShine\Resources\DeviceType\Pages\DeviceTypeDetailPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<DeviceType, DeviceTypeIndexPage, DeviceTypeFormPage, DeviceTypeDetailPage>
 */
class DeviceTypeResource extends ModelResource
{
    protected string $model = DeviceType::class;
    protected string $title = 'DeviceTypes';


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
