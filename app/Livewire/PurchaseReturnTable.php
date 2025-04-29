<?php

namespace App\Livewire;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class PurchaseReturnTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $perPageOptions = [10, 25, 50, 100, -1];
    public $sortField = 'ref_no';
    public $sortDirection = 'asc';
    
    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'ref_no'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        if (!auth()->user()->can('product.view') && !auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }

        $this->search = request()->query('search', $this->search);
        $this->sortField = request()->query('sortField', $this->sortField);
        $this->sortDirection = request()->query('sortDirection', $this->sortDirection);
        $this->perPage = request()->query('perPage', $this->perPage);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatePerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Query
    private function getPurchaseReturns()
    {
        $cacheKey = sprintf(
            'purchase_returns_%s_%s_%s_%s',
            $this->search,
            $this->perPage,
            $this->sortField,
            $this->sortDirection
        );

        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
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
            ->paginate($this->perPage == -1 ? 9999 : $this->perPage);
        });
    }

    public function render()
    {
        return view('livewire.purchase-return-table', [
            'purchaseReturns' => $this->getPurchaseReturns()
        ]);
    }
}
