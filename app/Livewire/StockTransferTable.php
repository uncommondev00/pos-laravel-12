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

class StockTransferTable extends Component
{
    use WithPagination, WithSortingSearchPagination;


    public $start_date = '';
    public $end_date = '';
    public $status = '';
    public $location_id = '';


    public function mount()
    {
        $this->sortField = 'transaction_date'; // Different default sort field

        $this->mountWithSortingSearchPagination();

        $this->filterConfig = [
            'start_date' => '',
            'end_date' => '',
            'status' => '',
            'location_id' => '',
        ];
    }


    public function render()
    {
        $business_id = request()->session()->get('user.business_id');
        $edit_days = request()->session()->get('business.transaction_edit_days');

        $query = Transaction::join(
            'business_locations AS l1',
            'transactions.location_id',
            '=',
            'l1.id'
        )
            ->join('transactions as t2', 't2.transfer_parent_id', '=', 'transactions.id')
            ->join(
                'business_locations AS l2',
                't2.location_id',
                '=',
                'l2.id'
            )
            ->where('transactions.business_id', $business_id)
            ->where('transactions.type', 'sell_transfer')
            ->select(
                'transactions.id',
                'transactions.transaction_date',
                'transactions.ref_no',
                'l1.name as location_from',
                'l2.name as location_to',
                'transactions.final_total',
                'transactions.shipping_charges',
                'transactions.additional_notes',
                'transactions.status'
            );

        // Apply filters
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('transactions.ref_no', 'like', '%' . $this->search . '%')
                    ->orWhere('l1.name', 'like', '%' . $this->search . '%')
                    ->orWhere('l2.name', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereDate('transactions.transaction_date', '>=', $this->start_date)
                ->whereDate('transactions.transaction_date', '<=', $this->end_date);
        }

        if (!empty($this->status)) {
            $query->where('transactions.status', $this->status);
        }

        if (!empty($this->location_id)) {
            $query->where('transactions.location_id', $this->location_id);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $stock_transfers = $query->paginate($this->perPage);

        // Calculate edit days for each transfer
        $today = Carbon::now();
        foreach ($stock_transfers as $transfer) {
            $date = Carbon::parse($transfer->transaction_date)->addDays($edit_days);
            $transfer->can_edit = $date->gte($today);
        }

        return view('livewire.stock-transfer-table', [
            'stock_transfers' => $stock_transfers,
            'edit_days' => $edit_days
        ]);
    }
}
