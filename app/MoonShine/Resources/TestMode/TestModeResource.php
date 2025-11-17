<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\TestMode;

use Illuminate\Database\Eloquent\Model;
use App\Models\TestMode;
use App\MoonShine\Resources\DeviceModel\DeviceModelResource;
use App\MoonShine\Resources\TestMode\Pages\TestModeIndexPage;
use App\MoonShine\Resources\TestMode\Pages\TestModeFormPage;
use App\MoonShine\Resources\TestMode\Pages\TestModeDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<TestMode, TestModeIndexPage, TestModeFormPage, TestModeDetailPage>
 */
class TestModeResource extends ModelResource
{
    protected string $model = TestMode::class;

    protected string $title = 'TestModes';

    public function fields(): array
    {
        return [
            ID::make(),
            BelongsTo::make('Device Model', 'deviceModel', DeviceModelResource::class),
            Text::make('Entry Combination', 'entry_combination'),
            Text::make('Exit Combination', 'exit_combination'),
            Textarea::make('Notes'),
            Text::make('Image URL', 'image_url'),
        ];
    }

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
