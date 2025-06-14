<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Contact;
use App\Models\BusinessLocation;
use App\Traits\WithSortingSearchPagination;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Utils\ModuleUtil;

class SellTable extends Component
{
    use WithPagination, WithSortingSearchPagination;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public $location_id = '';
    public $customer_id = '';
    public $payment_status = '';
    public $start_date = '';
    public $end_date = '';

    public $businessLocations = [];
    public $customers = [];


    protected $moduleUtil;

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
        $this->sortField = 'ref_no';
        $this->mountWithSortingSearchPagination();

        $this->filterConfig = [
            'location_id' => '',
            'supplier_id' => '',
            'payment_status' => '',
            'start_date' => '',
            'end_date' => ''
        ];
    }
    private function baseQuery()
    {
        $businessId = session('user.business_id');
        $permittedLocations = auth()->user()->permitted_locations();

        $query = Transaction::query()
            ->with(['contact', 'return_parent'])
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
                // DB::raw('(SELECT COUNT(*) FROM transactions as sr WHERE sr.return_parent_id = transactions.id) as return_exists'),
                // DB::raw('(SELECT COALESCE(SUM(final_total), 0) FROM transactions as sr WHERE sr.return_parent_id = transactions.id) as amount_return'),
                // DB::raw('(SELECT COUNT(1) FROM transactions sr WHERE sr.return_parent_id = transactions.id) as return_exists'),
                // DB::raw('COALESCE((SELECT SUM(final_total) FROM transactions sr WHERE sr.return_parent_id = transactions.id), 0) as amount_return'),
                DB::raw('(SELECT SUM(TP2.amount) FROM transaction_payments AS TP2 WHERE
                    TP2.transaction_id=transactions.return_parent_id ) as return_paid'),
                DB::raw('COALESCE(SUM(IF(tp.is_return = 1,-1*tp.amount,tp.amount)), 0) as total_paid'),
                'transactions.return_parent_id as return_transaction_id'
            ])
            ->leftJoin('transaction_payments as tp', 'transactions.id', '=', 'tp.transaction_id')
            //->leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
            ->where('transactions.business_id', $businessId)
            ->where('transactions.type', 'sell')
            ->where('transactions.status', 'final');

        if ($permittedLocations !== 'all') {
            $query->whereIn('transactions.location_id', $permittedLocations);
        }

        return $query->groupBy('transactions.id');
    }

    public function getSales()
    {
        $query = $this->baseQuery();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('transactions.invoice_no', 'like', '%' . $this->search . '%')
                    ->orWhereHas('contact', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
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

        return $query->orderBy($this->sortField, $this->sortDirection)
            ->latest('transactions.transaction_date')
            ->take(50)
            ->paginate($this->perPage == -1 ? PHP_INT_MAX : $this->perPage);
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
