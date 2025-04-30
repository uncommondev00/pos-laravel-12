<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Printer;

class PrinterTable extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $business_id = auth()->user()->business_id; // or however you get $business_id

        $printers = Printer::where('business_id', $business_id)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->select([
                'id',
                'name',
                'connection_type',
                'capability_profile',
                'char_per_line',
                'ip_address',
                'port',
                'path',
            ])
            ->paginate(10);

        return view('livewire.printer-table', [
            'printers' => $printers,
        ]);
    }

    public function deletePrinter($id)
    {
        $printer = Printer::findOrFail($id);

        $printer->delete();

        session()->flash('message', __('Printer deleted successfully.'));
    }
}
