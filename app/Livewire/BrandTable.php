<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Brands;
use Livewire\WithPagination;

class BrandTable extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';
    
    protected $listeners = ['refreshTable' => '$refresh'];

    public function mount()
    {
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
            ->paginate(10);

        return view('livewire.brand-table', [
            'brands' => $brands
        ]);
    }
}
