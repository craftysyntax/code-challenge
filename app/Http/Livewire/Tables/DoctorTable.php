<?php

namespace App\Http\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Doctor;
use App\Models\Test;
use Carbon\Carbon;

class DoctorTable extends DataTableComponent
{
    protected $model = Doctor::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    /**
     * Add test count to the query
     *
     * @return Builder
     */
    public function builder(): Builder
    {
        return Doctor::query()->withCount('tests');
    }

    /**
     * Set up the table columns
     *
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->searchable()
                ->sortable(),
            Column::make("Specialty", "specialty")
                ->searchable()
                ->sortable(),
            Column::make("Clinic", "clinic.name")
                ->searchable()
                ->sortable(),
            Column::make("Clinic", "clinic.address")
                ->sortable(),
            Column::make("Tests", 'id')
                ->format(
                    fn ($value, $row, Column $column) => $row->tests_count
                )
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->format(function ($value) {
                    return Carbon::parse($value)->toFormattedDayDateString();
                })
                ->sortable(),
            LinkColumn::make('Edit')
                ->title(fn ($row) => 'Edit')
                ->location(fn ($row) => route('doctors.edit', $row)),
            LinkColumn::make('Show')
                ->title(fn ($row) => 'Show')
                ->location(fn ($row) => route('doctors.show', $row)),
        ];
    }

    /**
     * Sets up the checkboxes for merging
     *
     * @return array
     */
    public function bulkActions(): array
    {
        return [
            'merge' => 'Merge'
        ];
    }

    /**
     * Merges doctors to the first selected
     * but only if of the same clinic
     *
     * @return void
     */
    public function merge()
    {
        $selected = $this->getSelected();

        // Get the Doctor we are merging into
        $into = array_shift($selected);
        $doctor = Doctor::where('id', $into)->first();

        // Will only merge Doctors that are of the same clinic
        while ($merged = Doctor::where('clinic_id', $doctor->clinic_id)->whereIn('id', $selected)->first()) {
            $tests = Test::whereIn('referring_doctor_id', $merged->id)->update(['reffering_doctor_id' => $merged->id]);
            $merged->delete();
        }
    }
}
