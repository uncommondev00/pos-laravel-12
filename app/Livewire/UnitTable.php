<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Unit;
use Livewire\WithPagination;

class UnitTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $perPageOptions = [10, 25, 50, 100, -1];
    public $sortField = 'actual_name';
    public $sortDirection = 'asc';
    
    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'actual_name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        if (!auth()->user()->can('unit.view') && !auth()->user()->can('unit.create')) {
            abort(403, 'Unauthorized action.');
        }

        $this->search = request()->query('search', $this->search);
        $this->sortField = request()->query('sortField', $this->sortField);
        $this->sortDirection = request()->query('sortDirection', $this->sortDirection);
        $this->perPage = request()->query('perPage', $this->perPage);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatePerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
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
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage == -1 ? 9999 : $this->perPage);

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
