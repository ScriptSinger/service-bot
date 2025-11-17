<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DeviceModel;

use Illuminate\Database\Eloquent\Model;
use App\Models\DeviceModel;
use App\MoonShine\Resources\Brand\BrandResource;
use App\MoonShine\Resources\DeviceModel\Pages\DeviceModelIndexPage;
use App\MoonShine\Resources\DeviceModel\Pages\DeviceModelFormPage;
use App\MoonShine\Resources\DeviceModel\Pages\DeviceModelDetailPage;
use App\MoonShine\Resources\ErrorCode\ErrorCodeResource;
use App\MoonShine\Resources\Manual\ManualResource;
use App\MoonShine\Resources\TestMode\TestModeResource;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<DeviceModel, DeviceModelIndexPage, DeviceModelFormPage, DeviceModelDetailPage>
 */
class DeviceModelResource extends ModelResource
{
    protected string $model = DeviceModel::class;

    protected string $title = 'DeviceModels';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Brand', 'brand', BrandResource::class),
            Text::make('Name'),
            Textarea::make('Description'),
            Number::make('Year From', 'year_from'),
            Number::make('Year To', 'year_to'),
            Text::make('Image URL', 'image_url'),
            // SwitchBoolean::make('Active'),
            HasMany::make('Manuals', 'manuals', ManualResource::class),
            HasMany::make('Test Modes', 'testModes', TestModeResource::class),
            HasMany::make('Error Codes', 'errorCodes', ErrorCodeResource::class),
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
}
