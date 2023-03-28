<?php

namespace App\Http\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Test;
use Carbon\Carbon;

class TestTable extends DataTableComponent
{
    protected $model = Test::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    /**
     * Add Referring Dr to the query
     *
     * @return Builder
     */
    public function builder(): Builder
    {
        return Test::query();
    }

    /**
     * Set up the columns
     *
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->format(function ($value) {
                    return Carbon::parse($value)->toFormattedDayDateString();
                })
                ->sortable(),
            Column::make("Name")
                ->searchable()
                ->sortable(),
            Column::make("Description")
                ->format(
                    fn ($value, $row, Column $column) => substr($row->description, 0, 100) . '...' // Truncate the Description for better UI
                )
                ->html()
                ->searchable()
                ->sortable()
        ];
    }
}
