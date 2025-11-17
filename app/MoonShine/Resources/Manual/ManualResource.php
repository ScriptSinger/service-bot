<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Manual;

use Illuminate\Database\Eloquent\Model;
use App\Models\Manual;
use App\MoonShine\Resources\DeviceModel\DeviceModelResource;
use App\MoonShine\Resources\Manual\Pages\ManualIndexPage;
use App\MoonShine\Resources\Manual\Pages\ManualFormPage;
use App\MoonShine\Resources\Manual\Pages\ManualDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Manual, ManualIndexPage, ManualFormPage, ManualDetailPage>
 */
class ManualResource extends ModelResource
{
    protected string $model = Manual::class;

    protected string $title = 'Manuals';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Device Model', 'deviceModel', DeviceModelResource::class),
            Text::make('Title'),
            Text::make('File URL', 'file_url'),
            Text::make('Language'),
        ];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            ManualIndexPage::class,
            ManualFormPage::class,
            ManualDetailPage::class,
        ];
    }
}
