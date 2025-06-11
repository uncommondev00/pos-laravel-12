<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Brands;
use Livewire\WithPagination;
use App\Traits\WithSortingSearchPagination;

class BrandTable extends Component
{
    use WithPagination, WithSortingSearchPagination;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->mountWithSortingSearchPagination();
        
        if (!auth()->user()->can('brand.view') && !auth()->user()->can('brand.create')) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function render()
    {
        $business_id = request()->session()->get('user.business_id');

        $brands = Brands::where('business_id', $business_id)
            ->select(['name', 'description', 'id'])
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage == -1 ? 9999 : $this->perPage);

        return view('livewire.brand-table', [
            'brands' => $brands
        ]);
    }
}
