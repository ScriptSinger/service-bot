<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\BroadcastMessage;

use Illuminate\Database\Eloquent\Model;
use App\Models\BroadcastMessage;
use App\MoonShine\Resources\BroadcastMessage\Pages\BroadcastMessageIndexPage;
use App\MoonShine\Resources\BroadcastMessage\Pages\BroadcastMessageFormPage;
use App\MoonShine\Resources\BroadcastMessage\Pages\BroadcastMessageDetailPage;
use App\MoonShine\Resources\TelegramUser\TelegramUserResource;
use Illuminate\Support\Facades\Log;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;


// Импорты атрибутов MoonShine
use MoonShine\Crud\Attributes\SaveHandler;
use MoonShine\Crud\Attributes\DestroyHandler;
use MoonShine\Crud\Attributes\MassDestroyHandler;

// Импорты твоих обработчиков
use App\MoonShine\Resources\BroadcastMessage\Handlers\BroadcastMessageSaveHandler;
use App\MoonShine\Resources\BroadcastMessage\Handlers\BroadcastMessageDestroyHandler;
use App\MoonShine\Resources\BroadcastMessage\Handlers\BroadcastMessageMassDestroyHandler;

/**
 * @extends ModelResource<BroadcastMessage, BroadcastMessageIndexPage, BroadcastMessageFormPage, BroadcastMessageDetailPage>
 */


#[SaveHandler(BroadcastMessageSaveHandler::class)]

class BroadcastMessageResource extends ModelResource
{
    protected string $model = BroadcastMessage::class;

    protected string $title = 'BroadcastMessages';


    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Message', 'message'),
            Text::make('Status', 'status'),
            Text::make('Receivers Count', 'receivers_count'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make('Broadcast Message Details', [
                ID::make()->readonly(),

                Textarea::make('Message', 'message')
                    ->required()
                    ->hint('Текст сообщения для рассылки'),

                Text::make('Status', 'status')
                    ->readonly()
                    ->hint('Статус рассылки зависит от очереди'),

                Number::make('Receivers Count', 'receivers_count')
                    ->readonly()
                    ->hint('Количество выбранных получателей'),

                BelongsToMany::make(
                    'Receivers',
                    'telegramUsers',
                    fn($item) => $item->name,
                    TelegramUserResource::class
                )
                    ->nullable()
                    ->searchable()
                    ->hint('Выберите пользователей для рассылки'),
            ]),
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make(),
        ];
    }


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
