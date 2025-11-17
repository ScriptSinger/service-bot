<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\Palettes\PurplePalette;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use App\MoonShine\Resources\DeviceType\DeviceTypeResource;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\Brand\BrandResource;
use App\MoonShine\Resources\DeviceModel\DeviceModelResource;
use App\MoonShine\Resources\Manual\ManualResource;
use App\MoonShine\Resources\TestMode\TestModeResource;
use App\MoonShine\Resources\ErrorCode\ErrorCodeResource;

final class MoonShineLayout extends AppLayout
{
    /**
     * @var null|class-string<PaletteContract>
     */
    protected ?string $palette = PurplePalette::class;

    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            ...parent::menu(),
            MenuItem::make(DeviceTypeResource::class, 'DeviceTypes'),
            MenuItem::make(BrandResource::class, 'Brands'),
            MenuItem::make(DeviceModelResource::class, 'DeviceModels'),
            MenuItem::make(ManualResource::class, 'Manuals'),
            MenuItem::make(TestModeResource::class, 'TestModes'),
            MenuItem::make(ErrorCodeResource::class, 'ErrorCodes'),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }
}
