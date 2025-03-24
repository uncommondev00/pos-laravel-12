<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RolesTable extends Component
{
    use WithPagination;

    public $search = ''; // Search input
    public $perPage = 5; // Items per page
    public $perPageOptions = [5, 10, 25, 50, 100]; // Dropdown options
    public $sortColumn = 'name'; // Default sort column
    public $sortDirection = 'asc'; // Default sort direction

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search',
        'perPage'
    ];

    public function updatingSearch(){
        $this->resetPage();
    }

    public function updatingPerPage(){
        $this->resetPage();
    }

    public function updatePerPage()
     {
         $this->resetPage();
     }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        if (!auth()->user()->can('roles.view')) {
            abort(403, 'Unauthorized action.');
        }

            $business_id = request()->session()->get('user.business_id');

            $roles = Role::where('business_id', $business_id)
                        ->where('id', '!=', 1)
                        ->select(['name', 'id', 'is_default', 'business_id']);

            $this->search = trim($this->search);

            if (!empty($this->search)) {
                $roles->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                });
            }
    
            $roles = $roles->orderBy($this->sortColumn, $this->sortDirection)
                ->paginate($this->perPage);

        return view('livewire.roles-table', ['roles' => $roles]);
    }
}
