<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Contact;
use App\Models\BusinessLocation;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Utils\ModuleUtil;

class SellTable extends Component
{
    use WithPagination;


    public $search = '';
    public $location_id = '';
    public $customer_id = '';
    public $payment_status = '';
    public $perPage = 10;
    public $perPageOptions = [10, 25, 50, 100, -1];
    public $sortField = 'ref_no';
    public $sortDirection = 'asc';

    public $businessLocations = [];
    public $customers = [];

    protected $paginationTheme = 'bootstrap';

    protected $moduleUtil;

    protected $queryString = [
        'location_id' => ['except' => ''],
        'customer_id' => ['except' => ''],
        'payment_status' => ['except' => ''],
        'search' => ['except' => ''],
        'sortField' => ['except' => 'ref_no'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    public function boot(ModuleUtil $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;

        $businessId = session('user.business_id');
        $this->businessLocations = Cache::remember(
            'business_locations_' . $businessId,
            now()->addHours(24),
            fn() => BusinessLocation::forDropdown($businessId, false)
        );

        $this->customers = Cache::remember(
            'customers_' . $businessId,
            now()->addHours(24),
            fn() => Contact::customersDropdown($businessId, false)
        );
    }

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
        $this->location_id = request()->query('location_id', $this->location_id);
        $this->customer_id = request()->query('supplier_id', $this->customer_id);
        $this->payment_status = request()->query('payment_status', $this->payment_status);
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

    private function baseQuery()
    {
        $businessId = session('user.business_id');
        $permittedLocations = auth()->user()->permitted_locations();

        $query = Transaction::query()
            ->with(['contact:id,name', 'location:id,name'])
            ->select([
                'transactions.id',
                'transactions.transaction_date',
                'transactions.invoice_no',
                'transactions.is_direct_sale',
                'transactions.payment_status',
                'transactions.final_total',
                'transactions.tax_amount',
                'transactions.discount_amount',
                'transactions.discount_type',
                'transactions.total_before_tax',
                'transactions.is_suspend',
                DB::raw('(SELECT SUM(TP2.amount) FROM transaction_payments AS TP2 WHERE
                        TP2.transaction_id=transactions.return_parent_id ) as return_paid'),
                DB::raw('COALESCE(SUM(IF(tp.is_return = 1,-1*tp.amount,tp.amount)), 0) as total_paid'),
                // DB::raw('(SELECT COUNT(*) FROM transactions as sr WHERE sr.return_parent_id = transactions.id) as return_exists'),
                // DB::raw('(SELECT COALESCE(SUM(final_total), 0) FROM transactions as sr WHERE sr.return_parent_id = transactions.id) as amount_return'),
                // DB::raw('(SELECT COUNT(1) FROM transactions sr WHERE sr.return_parent_id = transactions.id) as return_exists'),
                // DB::raw('COALESCE((SELECT SUM(final_total) FROM transactions sr WHERE sr.return_parent_id = transactions.id), 0) as amount_return'),
                'transactions.return_parent_id as return_transaction_id'
            ])
            ->leftJoin('transaction_payments as tp', 'transactions.id', '=', 'tp.transaction_id')
            ->where('transactions.business_id', $businessId)
            ->where('transactions.type', 'sell')
            ->where('transactions.status', 'final')
            ->limit(10);

        if ($permittedLocations !== 'all') {
            $query->whereIn('transactions.location_id', $permittedLocations);
        }

        return $query->groupBy('transactions.id');
    }

    public function getSales()
    {
        $cacheKey = $this->getCacheKey();

        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $query = $this->baseQuery();

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('transactions.invoice_no', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->payment_status) {
                $query->where('transactions.payment_status', $this->payment_status);
            }

            if ($this->location_id) {
                $query->where('transactions.location_id', $this->location_id);
            }

            if ($this->customer_id) {
                $query->where('transactions.contact_id', $this->customer_id);
            }


            return $query->latest('transactions.transaction_date')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage == -1 ? 9999 : $this->perPage);
        });
    }

    private function getCacheKey(): string
    {
        return sprintf(
            'sales_table_%s_%s_%s_%s_%d',
            $this->search,
            $this->payment_status,
            $this->location_id,
            $this->customer_id,
            $this->perPage
        );
    }
    public function render()
    {
        return view('livewire.sell-table', [
            'sales' => $this->getSales(),
            'business_locations' => $this->businessLocations,
            'customers' => $this->customers,
            'is_woocommerce' => $this->moduleUtil->isModuleInstalled('Woocommerce'),
        ]);
    }
}
