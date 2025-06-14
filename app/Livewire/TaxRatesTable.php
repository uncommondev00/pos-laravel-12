<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TaxRate;

class TaxRatesTable extends Component
{
    use WithPagination;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public $search = '';

    public function render()
    {
        $business_id = session()->get('user.business_id');

        $tax_rates = TaxRate::where('business_id', $business_id)
            ->where('is_tax_group', '0')
            ->select('id', 'name', 'amount')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(2);


        $tax_groups = TaxRate::where('business_id', $business_id)
            ->where('is_tax_group', '1')
            ->with(['sub_taxes'])->paginate(2);

        return view('livewire.tax-rates-table', [
            'tax_rates' => $tax_rates,
            'tax_groups' => $tax_groups
        ]);
    }
}
