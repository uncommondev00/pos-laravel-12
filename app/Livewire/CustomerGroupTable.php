<?php

namespace App\Livewire;

use App\Models\CustomerGroup;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class CustomerGroupTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $perPageOptions = [10, 25, 50, 100, -1];
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
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

        $customer_groups = $customer_groups->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage == -1 ? 9999 : $this->perPage);

        return view('livewire.customer-group-table', ['customer_groups' => $customer_groups]);
    }
}
