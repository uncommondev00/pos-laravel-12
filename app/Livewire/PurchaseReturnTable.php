<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Traits\WithSortingSearchPagination;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class PurchaseReturnTable extends Component
{
    use WithPagination, WithSortingSearchPagination;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        if (!auth()->user()->can('purchase.view') && !auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }

        $this->sortField = 'ref_no';
        $this->mountWithSortingSearchPagination();
    }


    private function getPurchaseReturns()
    {
        $query = Transaction::query()
            ->select([
                'transactions.id',
                'transactions.transaction_date',
                'transactions.ref_no',
                'contacts.name as supplier_name',
                'transactions.status',
                'transactions.payment_status',
                'transactions.final_total',
                'transactions.return_parent_id',
                'BS.name as location_name',
                'T.ref_no as parent_purchase',
                DB::raw('COALESCE(SUM(TP.amount), 0) as amount_paid')
            ])
            ->leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
            ->join('business_locations AS BS', 'transactions.location_id', '=', 'BS.id')
            ->join('transactions AS T', 'transactions.return_parent_id', '=', 'T.id')
            ->leftJoin('transaction_payments AS TP', 'transactions.id', '=', 'TP.transaction_id')
            ->where('transactions.business_id', session('user.business_id'))
            ->where('transactions.type', 'purchase_return');

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('transactions.ref_no', 'like', '%' . $this->search . '%')
                    ->orWhere('contacts.name', 'like', '%' . $this->search . '%')
                    ->orWhere('T.ref_no', 'like', '%' . $this->search . '%');
            });
        }

        // Apply location permissions
        $permitted_locations = auth()->user()->permitted_locations();
        if ($permitted_locations !== 'all') {
            $query->whereIn('transactions.location_id', $permitted_locations);
        }

        // Group by to handle the aggregation
        $query->groupBy([
            'transactions.id',
            'transactions.transaction_date',
            'transactions.ref_no',
            'contacts.name',
            'transactions.status',
            'transactions.payment_status',
            'transactions.final_total',
            'transactions.return_parent_id',
            'BS.name',
            'T.ref_no'
        ]);

        return $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage == -1 ? PHP_INT_MAX : $this->perPage);
    }

    public function render()
    {
        return view('livewire.purchase-return-table', [
            'purchaseReturns' => $this->getPurchaseReturns()
        ]);
    }
}
