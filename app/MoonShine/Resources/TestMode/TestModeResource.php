<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\TestMode;

use App\Models\TestMode;
use App\MoonShine\Resources\DeviceModel\DeviceModelResource;
use App\MoonShine\Resources\TestMode\Pages\TestModeIndexPage;
use App\MoonShine\Resources\TestMode\Pages\TestModeFormPage;
use App\MoonShine\Resources\TestMode\Pages\TestModeDetailPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<TestMode, TestModeIndexPage, TestModeFormPage, TestModeDetailPage>
 */
class TestModeResource extends ModelResource
{
    protected string $model = TestMode::class;

    protected string $title = 'TestModes';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            TestModeIndexPage::class,
            TestModeFormPage::class,
            TestModeDetailPage::class,
        ];
    }
}
