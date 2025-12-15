<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Manual;

use App\Models\Manual;
use App\MoonShine\Resources\Manual\Pages\ManualIndexPage;
use App\MoonShine\Resources\Manual\Pages\ManualFormPage;
use App\MoonShine\Resources\Manual\Pages\ManualDetailPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Manual, ManualIndexPage, ManualFormPage, ManualDetailPage>
 */
class ManualResource extends ModelResource
{
    protected string $model = Manual::class;
    protected string $title = 'Manuals';

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

    public function search(): array
    {
        return [
            'deviceModel.name',
        ];
    }
}
