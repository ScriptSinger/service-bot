<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ErrorCode;

use Illuminate\Database\Eloquent\Model;
use App\Models\ErrorCode;
use App\MoonShine\Resources\DeviceModel\DeviceModelResource;
use App\MoonShine\Resources\ErrorCode\Pages\ErrorCodeIndexPage;
use App\MoonShine\Resources\ErrorCode\Pages\ErrorCodeFormPage;
use App\MoonShine\Resources\ErrorCode\Pages\ErrorCodeDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<ErrorCode, ErrorCodeIndexPage, ErrorCodeFormPage, ErrorCodeDetailPage>
 */
class ErrorCodeResource extends ModelResource
{
    protected string $model = ErrorCode::class;

    protected string $title = 'ErrorCodes';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            ErrorCodeIndexPage::class,
            ErrorCodeFormPage::class,
            ErrorCodeDetailPage::class,
        ];
    }
}
