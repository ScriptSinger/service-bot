<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Manual\Pages;

use App\MoonShine\Resources\DeviceModel\DeviceModelResource;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use App\MoonShine\Resources\Manual\ManualResource;
use App\MoonShine\Resources\ManualFile\ManualFileResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Textarea;
use Throwable;


/**
 * @extends FormPage<ManualResource>
 */
class ManualFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                BelongsTo::make(
                    'Device Model',
                    'deviceModel',
                    fn($item) => $item->name,
                    DeviceModelResource::class
                )->searchable(),

                HasMany::make('Files', 'files', null, ManualFileResource::class)
                    ->fields([
                        ID::make()->sortable(),
                        Text::make('File Name', 'file_name'),
                        Textarea::make('Description'),
                        Text::make('Language', 'language'),

                        File::make('File', 'file_url')
                            ->disk('public')
                            ->dir('manuals/files')
                            ->allowedExtensions(['pdf'])
                            ->removable(),

                        Date::make('Created At', 'created_at')
                            ->format('Y-m-d H:i:s')
                            ->sortable(),

                        Date::make('Updated At', 'updated_at')
                            ->format('Y-m-d H:i:s')
                            ->sortable(),
                    ])
                    ->creatable()
            ]),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    protected function formButtons(): ListOf
    {
        return parent::formButtons();
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [];
    }

    /**
     * @param  FormBuilder  $component
     *
     * @return FormBuilder
     */
    protected function modifyFormComponent(FormBuilderContract $component): FormBuilderContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
