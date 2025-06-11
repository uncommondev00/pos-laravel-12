<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SellingPriceGroup;
use Livewire\WithPagination;
use App\Traits\WithSortingSearchPagination;

class SellingPriceGroupTable extends Component
{
    use WithPagination, WithSortingSearchPagination;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->mountWithSortingSearchPagination();
        
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }

    }

    public function render()
    {
        
        $business_id = request()->session()->get('user.business_id');

        $price_groups = SellingPriceGroup::where('business_id', $business_id)
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->select(['name', 'description', 'id'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage == -1 ? 9999 : $this->perPage);

        return view('livewire.selling-price-group-table', [
            'price_groups' => $price_groups
        ]);
    }
}
