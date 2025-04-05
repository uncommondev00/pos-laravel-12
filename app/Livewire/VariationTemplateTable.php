<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\VariationTemplate;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class VariationTemplateTable extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $business_id = request()->session()->get('user.business_id');

        $variations = VariationTemplate::where('business_id', $business_id)
            ->with(['values'])
            ->select('id', 'name', DB::raw("(SELECT COUNT(id) FROM product_variations WHERE product_variations.variation_template_id=variation_templates.id) as total_pv"))
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.variation-template-table', compact('variations'));
    }
}
