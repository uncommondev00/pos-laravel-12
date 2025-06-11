<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Barcode;

class BarcodeTable extends Component
{
    use WithPagination;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public $search = '';

    public function render()
    {
        $business_id = auth()->user()->business_id;

        $barcodes = Barcode::where('business_id', $business_id)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->select(['id', 'name', 'description', 'is_default'])
            ->paginate(10);

        return view('livewire.barcode-table', [
            'barcodes' => $barcodes,
        ]);
    }

    public function deleteBarcode($id)
    {
        $barcode = Barcode::findOrFail($id);

        if ($barcode->is_default) {
            session()->flash('error', __('Cannot delete default barcode.'));
            return;
        }

        $barcode->delete();

        session()->flash('message', __('Barcode deleted successfully.'));
    }

    public function setDefault($id)
    {
        // Unset previous default
        Barcode::where('business_id', auth()->user()->business_id)
            ->update(['is_default' => 0]);

        // Set this as default
        $barcode = Barcode::findOrFail($id);
        $barcode->is_default = 1;
        $barcode->save();

        session()->flash('message', __('Barcode set as default.'));
    }
}
