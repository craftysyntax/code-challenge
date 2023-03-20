<?php

namespace App\Http\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Clinic;
use App\Models\Doctor;
use Carbon\Carbon;

class ClinicTable extends DataTableComponent
{

    protected $model = Clinic::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
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
            Column::make("Name", "name")
                ->sortable()
                ->searchable(),
            Column::make("Address", "address")
                ->sortable()
                ->searchable(),
            Column::make("Updated at", "updated_at")
                ->format(function ($value) {
                    return Carbon::parse($value)->toFormattedDayDateString();
                })
                ->sortable()
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
            'merge' => 'Merge',
        ];
    }

    /**
     * Merge Clinics abd reassign doctors to the remaining clinic
     * First selected clinic is the destition
     *
     * @return void
     */
    public function merge()
    {
        $clinics = $this->getSelected();
        $into = array_shift($clinics);

        // Move doctors to the clinic that we are merging into
        $doctors = Doctor::whereIn('clinic_id', $clinics)->update(['clinic_id' => $into]);

        // Delete the merged clinics
        Clinic::destroy($clinics);
    }
}
