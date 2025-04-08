<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\BusinessLocation;
use App\Models\Contact;
use App\Utils\TransactionUtil;
use App\Utils\BusinessUtil;
use App\Utils\ModuleUtil;

class SellTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $customerId = '';
    public $locationId = '';
    public $paymentStatus = '';
    public $isDirectSale = '';
    public $startDate = '';
    public $endDate = '';
    public $createdBy = '';
    public $commissionAgent = '';
    public $resWaiterId = '';
    public $subType = '';
    public $onlyWoocommerce = false;
    public $listFor = '';
    public $sortField = 'transaction_date';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'customerId' => ['except' => ''],
        'locationId' => ['except' => ''],
        'paymentStatus' => ['except' => ''],
        'isDirectSale' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'createdBy' => ['except' => ''],
        'commissionAgent' => ['except' => ''],
        'resWaiterId' => ['except' => ''],
        'subType' => ['except' => ''],
        'onlyWoocommerce' => ['except' => false],
        'listFor' => ['except' => ''],
    ];

    protected $listeners = ['refreshSales' => '$refresh'];

    private $transactionUtil;
    private $businessUtil;
    private $moduleUtil;

    public function __construct()
    {
        $this->transactionUtil = new TransactionUtil();
        $this->businessUtil = new BusinessUtil();
        $this->moduleUtil = new ModuleUtil();
    }

    public function render()
    {
        // Authorization check
    if (!auth()->user()->can('sell.view') && !auth()->user()->can('sell.create') && !auth()->user()->can('direct_sell.access')) {
        abort(403, 'Unauthorized action.');
    }

    $businessId = request()->session()->get('user.business_id');
    $isWoocommerce = $this->moduleUtil->isModuleInstalled('Woocommerce');

    // Cache key for the query
    $cacheKey = "sales_list_{$businessId}_{$this->perPage}_{$this->search}_{$this->customerId}_{$this->locationId}_{$this->paymentStatus}_{$this->isDirectSale}_{$this->startDate}_{$this->endDate}_{$this->sortField}_{$this->sortDirection}";

    // Cache the query result with pagination
    $sales = cache()->remember($cacheKey, now()->addMinutes(10), function () use ($businessId, $isWoocommerce) {
        $query = Transaction::query()
            ->with(['contact', 'location', 'payment_lines'])
            ->leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
            ->leftJoin('transaction_payments as tp', 'transactions.id', '=', 'tp.transaction_id')
            ->join('business_locations AS bl', 'transactions.location_id', '=', 'bl.id')
            ->leftJoin('transactions AS SR', 'transactions.id', '=', 'SR.return_parent_id')
            ->where('transactions.business_id', $businessId)
            ->where('transactions.type', 'sell')
            ->where('transactions.status', 'final')
            ->limit(1)
            ->select([
                'transactions.id',
                'transactions.transaction_date',
                'transactions.is_direct_sale',
                'transactions.invoice_no',
                'transactions.is_suspend',
                'contacts.name',
                'transactions.payment_status',
                'transactions.final_total',
                'transactions.tax_amount',
                'transactions.discount_amount',
                'transactions.discount_type',
                'transactions.total_before_tax',
                DB::raw('SUM(IF(tp.is_return = 1,-1*tp.amount,tp.amount)) as total_paid'),
                'bl.name as business_location',
                DB::raw('COUNT(SR.id) as return_exists'),
                DB::raw('(SELECT SUM(TP2.amount) FROM transaction_payments AS TP2 WHERE TP2.transaction_id=SR.id ) as return_paid'),
                DB::raw('COALESCE(SR.final_total, 0) as amount_return'),
                'SR.id as return_transaction_id'
            ]);

        // Add Woocommerce fields if module is installed
        if ($isWoocommerce) {
            $query->addSelect('transactions.woocommerce_order_id');
            if ($this->onlyWoocommerce) {
                $query->whereNotNull('transactions.woocommerce_order_id');
            }
        }

        // Apply filters and other conditions
        $this->applyFilters($query);

        // Permitted locations
        $permittedLocations = auth()->user()->permitted_locations();
        if ($permittedLocations != 'all') {
            $query->whereIn('transactions.location_id', $permittedLocations);
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection)
            ->groupBy('transactions.id');

        return $query->paginate($this->perPage);
    });

    return view('livewire.sell-table', [
        'sales' => $sales,
        'business_locations' => BusinessLocation::forDropdown($businessId, false),
        'customers' => Contact::customersDropdown($businessId, false),
        'isWoocommerce' => $isWoocommerce,
        'isSubscriptionEnabled' => $this->businessUtil->isModuleEnabled('subscription'),
    ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function applyFilters($query)
    {
        if (!empty($this->customerId)) {
            $query->where('contacts.id', $this->customerId);
        }

        if (!empty($this->locationId)) {
            $query->where('transactions.location_id', $this->locationId);
        }

        if (!empty($this->paymentStatus)) {
            $query->where('transactions.payment_status', $this->paymentStatus);
        }

        if (!empty($this->createdBy)) {
            $query->where('transactions.created_by', $this->createdBy);
        }

        if (!empty($this->commissionAgent)) {
            $query->where('transactions.commission_agent', $this->commissionAgent);
        }

        if (!empty($this->resWaiterId)) {
            $query->where('transactions.res_waiter_id', $this->resWaiterId);
        }

        if (!empty($this->subType)) {
            $query->where('transactions.sub_type', $this->subType);
        }

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->whereDate('transactions.transaction_date', '>=', $this->startDate)
                  ->whereDate('transactions.transaction_date', '<=', $this->endDate);
        }

        if ($this->isDirectSale !== '') {
            if ($this->isDirectSale == 0) {
                $query->where('transactions.is_direct_sale', 0)
                      ->whereNull('transactions.sub_type');
            } else {
                $query->where('transactions.is_direct_sale', 1);
            }
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('transactions.invoice_no', 'like', '%'.$this->search.'%')
                  ->orWhere('contacts.name', 'like', '%'.$this->search.'%');
            });
        }
    }

    public function resetFilters()
    {
        $this->reset([
            'customerId',
            'locationId',
            'paymentStatus',
            'isDirectSale',
            'startDate',
            'endDate',
            'createdBy',
            'commissionAgent',
            'resWaiterId',
            'subType',
            'onlyWoocommerce',
            'listFor',
            'search'
        ]);
    }

    public function getVatableProperty($transactionId)
    {
        return DB::table('transaction_sell_lines')
            ->where('transaction_id', $transactionId)
            ->select(DB::raw('SUM(IF(tax_id >= 2, 0, unit_price*quantity)) as vatable'))
            ->value('vatable');
    }

    public function getVatProperty($transactionId)
    {
        return DB::table('transaction_sell_lines')
            ->select(DB::raw('SUM(IF(tax_id > 1, 0, item_tax*quantity)) as vat'))
            ->where('transaction_id', $transactionId)
            ->value('vat');
    }

    public function getVatExemptProperty($transactionId)
    {
        return DB::table('transaction_sell_lines')
            ->select(DB::raw('SUM(IF(tax_id != 2, 0, unit_price*quantity)) as vat_exempt'))
            ->where('transaction_id', $transactionId)
            ->value('vat_exempt');
    }

    public function getVzrProperty($transactionId)
    {
        return DB::table('transaction_sell_lines')
            ->select(DB::raw('SUM(IF(tax_id != 3, 0, unit_price*quantity)) as vzr'))
            ->where('transaction_id', $transactionId)
            ->value('vzr');
    }
}
