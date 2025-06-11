<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InvoiceScheme;
use App\Models\InvoiceLayout;
use Illuminate\Support\Facades\Session;

class InvoiceSchemesTable extends Component
{
    public $schemes = [];
    public $invoiceLayouts = [];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $businessId = Session::get('user.business_id');

        $this->schemes = InvoiceScheme::where('business_id', $businessId)
            ->select(['id', 'name', 'scheme_type', 'prefix', 'start_number', 'invoice_count', 'total_digits', 'is_default'])
            ->get();

        $this->invoiceLayouts = InvoiceLayout::where('business_id', $businessId)
            ->with(['locations'])
            ->get();
    }

    public function edit($id)
    {
        // You can redirect to edit page
        return redirect()->route('invoice-schemes.edit', $id);
    }

    // public function delete($id)
    // {
    //     $scheme = InvoiceScheme::findOrFail($id);

    //     if (!$scheme->is_default) {
    //         $scheme->delete();
    //         session()->flash('message', __('messages.deleted_successfully'));
    //         $this->mount(); // reload the data
    //     }
    // }

    public function setDefault($id)
    {
        $businessId = Session::get('user.business_id');

        // Unset previous default
        InvoiceScheme::where('business_id', $businessId)
            ->update(['is_default' => 0]);

        // Set new default
        $scheme = InvoiceScheme::findOrFail($id);
        $scheme->is_default = 1;
        $scheme->save();

        session()->flash('message', __('barcode.set_as_default_successfully'));
        $this->mount(); // reload the data
    }

    public function render()
    {
        return view('livewire.invoice-schemes-table');
    }
}
