<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Unit;
use Livewire\WithPagination;

class UnitTable extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';
    
    protected $listeners = ['refreshTable' => '$refresh'];

    public function mount()
    {
        if (!auth()->user()->can('unit.view') && !auth()->user()->can('unit.create')) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function render()
    {
        $business_id = request()->session()->get('user.business_id');

        $units = Unit::where('business_id', $business_id)
            ->with(['base_unit'])
            ->select([
                'actual_name', 
                'short_name', 
                'allow_decimal', 
                'id',
                'base_unit_id', 
                'base_unit_multiplier'
            ])
            ->when($this->search, function($query) {
                $query->where('actual_name', 'like', '%' . $this->search . '%')
                    ->orWhere('short_name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.unit-table', [
            'units' => $units
        ]);
    }

    public function getFormattedName($unit)
    {
        if (!empty($unit->base_unit_id)) {
            return $unit->actual_name . ' (' . (float)$unit->base_unit_multiplier . $unit->base_unit->short_name . ')';
        }
        return $unit->actual_name;
    }
}
