<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\VariationTemplate;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Traits\WithSortingSearchPagination;

class VariationTemplateTable extends Component
{
    use WithPagination, WithSortingSearchPagination;

    public function mount()
    {
        $this->mountWithSortingSearchPagination();
    }

    public function render()
    {
        $business_id = request()->session()->get('user.business_id');

        $variations = VariationTemplate::where('business_id', $business_id)
            ->with(['values'])
            ->select('id', 'name', DB::raw("(SELECT COUNT(id) FROM product_variations WHERE product_variations.variation_template_id=variation_templates.id) as total_pv"))
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage == -1 ? 9999 : $this->perPage);

        return view('livewire.variation-template-table', compact('variations'));
    }
}
