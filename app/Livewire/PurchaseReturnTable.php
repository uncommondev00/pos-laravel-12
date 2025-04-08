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

    protected $paginationTheme = 'bootstrap';  // Add this line for Bootstrap pagination styling

    // Public Properties
    public $perPage = 10;
    public $search = '';
    public $supplierFilter = '';
    public $dateRange = '';
    public $startDate = '';
    public $endDate = '';
    public $sortField = 'transaction_date';
    public $sortDirection = 'desc';

    // Protected Properties
    protected $queryString = [
        'search' => ['except' => ''],
        'supplierFilter' => ['except' => ''],
        'dateRange' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'transaction_date'],
        'sortDirection' => ['except' => 'desc']
    ];

    // Lifecycle Hooks
    public function mount()
    {
        if (!auth()->user()->can('purchase.view') && !auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }
        $this->resetPage();

    }

    // Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSupplierFilter()
    {
        $this->resetPage();
    }

    public function updatingDateRange()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // Sorting
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }

    // Query
    private function getPurchaseReturns()
    {
        $cacheKey = sprintf(
            'purchase_returns_%s_%s_%s_%s_%d_%s_%s',
            $this->search,
            $this->supplierFilter,
            $this->startDate,
            $this->endDate,
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

            // Apply supplier filter
            if ($this->supplierFilter) {
                $query->where('contacts.id', $this->supplierFilter);
            }

            // Apply date range filter
            if ($this->startDate && $this->endDate) {
                $query->whereDate('transactions.transaction_date', '>=', $this->startDate)
                      ->whereDate('transactions.transaction_date', '<=', $this->endDate);
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
                        ->paginate($this->perPage);
        });
    }

    public function render()
    {
        return view('livewire.purchase-return-table', [
            'purchaseReturns' => $this->getPurchaseReturns()
        ]);
    }

     // Clear filters
     public function resetFilters()
     {
         $this->reset(['search', 'supplierFilter', 'dateRange', 'startDate', 'endDate']);
         $this->resetPage();
     }
}
