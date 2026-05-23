<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ManualFile\Pages;

use App\MoonShine\Resources\Manual\ManualResource;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Fields\ID;
use App\MoonShine\Resources\ManualFile\ManualFileResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\DateRange;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;


/**
 * @extends IndexPage<ManualFileResource>
 */
class ManualFileIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Title'),
            Textarea::make('Description'),
            Text::make('Language'),
            BelongsTo::make(
                'Manual',
                'manual',
                fn($item) => $item->display_name,
                ManualResource::class
            )->sortable(),
            Text::make('File URL', 'file_url')->sortable(),
            Date::make('Created At', 'created_at')->format('Y-m-d H:i:s')->sortable(),
            Date::make('Updated At', 'updated_at')->format('Y-m-d H:i:s')->sortable(),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @return list<FieldContract>
     */
    protected function filters(): iterable
    {
        return [
            Text::make('Title', 'title'),
            Text::make('Description', 'description'),
            Select::make('Language', 'language')
                ->options(config('languages'))
                ->nullable()
                ->searchable(),
            BelongsTo::make(
                'Manual',
                'manual',
                fn($item) => $item->display_name,
                ManualResource::class
            )
                ->nullable()
                ->searchable(),
            DateRange::make('Created At', 'created_at'),
            DateRange::make('Updated At', 'updated_at'),
        ];
    }

    /**
     * @return list<QueryTag>
     */
    protected function queryTags(): array
    {
        return [
            QueryTag::make(
                'No language',
                fn ($query) => $query->whereNull('language')
            ),
            ...collect(config('languages', []))
            ->map(
                fn (string $label, string $code): QueryTag => QueryTag::make(
                    $label,
                    fn ($query) => $query->where('language', $code)
                )
            )
            ->values()
            ->all(),
        ];
    }

    /**
     * @return list<Metric>
     */
    protected function metrics(): array
    {
        return [];
    }

    /**
     * @param  TableBuilder  $component
     *
     * @return TableBuilder
     */
    protected function modifyListComponent(ComponentContract $component): ComponentContract
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
