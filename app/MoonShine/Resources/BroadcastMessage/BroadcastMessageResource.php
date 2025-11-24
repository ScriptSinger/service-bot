<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\BroadcastMessage;

use Illuminate\Database\Eloquent\Model;
use App\Models\BroadcastMessage;
use App\MoonShine\Resources\BroadcastMessage\Pages\BroadcastMessageIndexPage;
use App\MoonShine\Resources\BroadcastMessage\Pages\BroadcastMessageFormPage;
use App\MoonShine\Resources\BroadcastMessage\Pages\BroadcastMessageDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<BroadcastMessage, BroadcastMessageIndexPage, BroadcastMessageFormPage, BroadcastMessageDetailPage>
 */
class BroadcastMessageResource extends ModelResource
{
    protected string $model = BroadcastMessage::class;

    protected string $title = 'BroadcastMessages';





    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            BroadcastMessageIndexPage::class,
            BroadcastMessageFormPage::class,
            BroadcastMessageDetailPage::class,
        ];
    }
}
