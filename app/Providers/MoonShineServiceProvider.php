<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRole\MoonShineUserRoleResource;
use App\MoonShine\Resources\DeviceType\DeviceTypeResource;
use App\MoonShine\Resources\Brand\BrandResource;
use App\MoonShine\Resources\DeviceModel\DeviceModelResource;
use App\MoonShine\Resources\Manual\ManualResource;
use App\MoonShine\Resources\ManualFile\ManualFileResource;
use App\MoonShine\Resources\TelegramUser\TelegramUserResource;
use App\MoonShine\Resources\User\UserResource;
use App\MoonShine\Resources\BroadcastMessage\BroadcastMessageResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  CoreContract<MoonShineConfigurator>  $core
     */
    public function boot(CoreContract $core): void
    {
        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                DeviceTypeResource::class,
                BrandResource::class,
                DeviceModelResource::class,
                ManualResource::class,
                ManualFileResource::class,
                TelegramUserResource::class,
                UserResource::class,
                BroadcastMessageResource::class,
            ])
            ->pages([
                ...$core->getConfig()->getPages(),
            ])
        ;
    }
}
