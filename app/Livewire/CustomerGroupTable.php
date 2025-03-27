<?php

namespace App\Livewire;

use App\Models\CustomerGroup;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class CustomerGroupTable extends Component
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
        if (!auth()->user()->can('customer.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $customer_groups = CustomerGroup::where('business_id', $business_id)
                            ->select(['name', 'amount', 'id']);

        $this->search = trim($this->search);

        if (!empty($this->search)) {
            $customer_groups->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $customer_groups = $customer_groups->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.customer-group-table', ['customer_groups' => $customer_groups]);
    }
}
