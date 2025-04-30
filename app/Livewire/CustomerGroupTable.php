<?php

namespace App\Livewire;

use App\Models\CustomerGroup;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Traits\WithSortingSearchPagination;

class CustomerGroupTable extends Component
{
    use WithPagination, WithSortingSearchPagination;

    public function mount()
    {
        $this->mountWithSortingSearchPagination();

        if (!auth()->user()->can('customer.view')) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function render()
    {

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
