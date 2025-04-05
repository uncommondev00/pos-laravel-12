<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SellingPriceGroup;
use Livewire\WithPagination;

class SellingPriceGroupTable extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';
    
    protected $listeners = ['refreshTable' => '$refresh'];

    public function render()
    {
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');

        $price_groups = SellingPriceGroup::where('business_id', $business_id)
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->select(['name', 'description', 'id'])
            ->paginate(10);

        return view('livewire.selling-price-group-table', [
            'price_groups' => $price_groups
        ]);
    }
}
