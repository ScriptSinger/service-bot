<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ManualFile;

use Illuminate\Database\Eloquent\Model;
use App\Models\ManualFile;
use App\MoonShine\Resources\ManualFile\Pages\ManualFileIndexPage;
use App\MoonShine\Resources\ManualFile\Pages\ManualFileFormPage;
use App\MoonShine\Resources\ManualFile\Pages\ManualFileDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<ManualFile, ManualFileIndexPage, ManualFileFormPage, ManualFileDetailPage>
 */
class ManualFileResource extends ModelResource
{
    protected string $model = ManualFile::class;

    protected string $title = 'ManualFiles';
    
    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            ManualFileIndexPage::class,
            ManualFileFormPage::class,
            ManualFileDetailPage::class,
        ];
    }
}
