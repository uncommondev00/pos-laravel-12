<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Traits\WithSortingSearchPagination;
use App\Utils\TransactionUtil;
use App\Utils\BusinessUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StockAdjustmentTable extends Component
{
    use WithPagination, WithSortingSearchPagination;

    public $start_date = '';
    public $end_date = '';
    public $location_id = '';

    public function mount()
    {

        $this->sortField = 'transaction_date'; // Different default sort field

        $this->mountWithSortingSearchPagination();

        $this->filterConfig = [
            'start_date' => '',
            'end_date' => '',
            'location_id' => '',
        ];

        if (!auth()->user()->can('purchase.view') && !auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function render()
    {
        $business_id = request()->session()->get('user.business_id');

        $query = Transaction::join(
            'business_locations AS BL',
            'transactions.location_id',
            '=',
            'BL.id'
        )
            ->where('transactions.business_id', $business_id)
            ->where('transactions.type', 'stock_adjustment')
            ->select(
                'transactions.id',
                'transaction_date',
                'ref_no',
                'BL.name as location_name',
                'adjustment_type',
                'final_total',
                'total_amount_recovered',
                'additional_notes'
            );

        $permitted_locations = auth()->user()->permitted_locations();
        if ($permitted_locations != 'all') {
            $query->whereIn('transactions.location_id', $permitted_locations);
        }

        $hide = '';
        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereBetween(DB::raw('date(transaction_date)'), [$this->start_date, $this->end_date]);
            $hide = 'hide';
        }

        if (!empty($this->location_id)) {
            $query->where('transactions.location_id', $this->location_id);
        }

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('transactions.ref_no', 'like', '%' . $this->search . '%')
                    ->orWhere('BL.name', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $stock_adjustments = $query->paginate($this->perPage);

        return view('livewire.stock-adjustment-table', [
            'stock_adjustments' => $stock_adjustments,
            'hide' => $hide
        ]);
    }
}
