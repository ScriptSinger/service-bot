<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\TelegramUser;

use App\Models\TelegramUser;
use App\MoonShine\Resources\TelegramUser\Pages\TelegramUserIndexPage;
use App\MoonShine\Resources\TelegramUser\Pages\TelegramUserFormPage;
use App\MoonShine\Resources\TelegramUser\Pages\TelegramUserDetailPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<TelegramUser, TelegramUserIndexPage, TelegramUserFormPage, TelegramUserDetailPage>
 */
class TelegramUserResource extends ModelResource
{
    protected string $model = TelegramUser::class;
    protected string $title = 'TelegramUsers';

    protected function indexFields(): iterable
    {
        return [
            ID::make(),
            Image::make('Avatar', 'avatar_path')
                ->disk('public'),
            Text::make('First Name', 'first_name')->sortable(),
            Text::make('Username', 'username')->sortable(),
            Text::make('Language Code', 'language_code')->sortable(),
            Date::make('Last Activity', 'last_activity')
                ->withTime()
                ->format('d.m.Y H:i:s')
        ];
    }

    protected function search(): array
    {
        return ['telegram_id', 'username', 'first_name'];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            TelegramUserIndexPage::class,
            TelegramUserFormPage::class,
            TelegramUserDetailPage::class,
        ];
    }
}
