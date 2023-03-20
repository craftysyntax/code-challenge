<?php

namespace App\Http\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Test;
use Carbon\Carbon;

class DoctorTestTable extends DataTableComponent
{
    public $referring_doctor_id = 1;

    protected $model = Test::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return Test::query()->where('referring_doctor_id', $this->referring_doctor_id)->with('referringDoctor');
    }

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
                ->searchable()
                ->sortable()
        ];
    }
}
