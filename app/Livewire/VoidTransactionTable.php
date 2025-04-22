<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VoidTransaction;
use App\Models\BusinessLocation;
use App\Models\Contact;
use App\Utils\TransactionUtil;
use App\Utils\BusinessUtil;
use App\Utils\ModuleUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VoidTransactionTable extends Component
{
    use WithPagination;

    public $search = '';
    public $start_date = '';
    public $end_date = '';
    public $payment_status = '';
    public $location_id = '';
    public $customer_id = '';
    public $is_direct_sale = '';
    public $commission_agent = '';
    public $res_waiter_id = '';
    public $sub_type = '';
    public $sortField = 'transaction_date';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $is_woocommerce = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'payment_status' => ['except' => ''],
        'location_id' => ['except' => ''],
        'customer_id' => ['except' => ''],
        'is_direct_sale' => ['except' => ''],
        'commission_agent' => ['except' => ''],
        'res_waiter_id' => ['except' => ''],
        'sub_type' => ['except' => ''],
        'sortField' => ['except' => 'transaction_date'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        if (!auth()->user()->can('sell.view')) {
            abort(403, 'Unauthorized action.');
        }

        $this->search = request()->get('search', '');
        $this->start_date = request()->get('start_date', '');
        $this->end_date = request()->get('end_date', '');
        $this->payment_status = request()->get('payment_status', '');
        $this->location_id = request()->get('location_id', '');
        $this->customer_id = request()->get('customer_id', '');
        $this->is_direct_sale = request()->get('is_direct_sale', '');
        $this->commission_agent = request()->get('commission_agent', '');
        $this->res_waiter_id = request()->get('res_waiter_id', '');
        $this->sub_type = request()->get('sub_type', '');
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $business_id = request()->session()->get('user.business_id');
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $macAddr = "";

        // Get MAC address
        $arp = `arp -a $ipAddress`;
        $lines = explode("\n", $arp);
        foreach($lines as $line) {
            $cols = preg_split('/\s+/', trim($line));
            if ($cols[0] == $ipAddress) {
                $macAddr = $cols[1];
            }
        }

        $is_woocommerce = false;

        $query = VoidTransaction::leftJoin('contacts', 'void_transactions.contact_id', '=', 'contacts.id')
            ->leftJoin('transaction_payments as tp', 'void_transactions.id', '=', 'tp.transaction_id')
            ->join('business_locations AS bl', 'void_transactions.location_id', '=', 'bl.id')
            ->leftJoin('void_transactions AS SR', 'void_transactions.id', '=', 'SR.return_parent_id')
            ->where('void_transactions.business_id', $business_id)
            ->where('void_transactions.mac_address', $macAddr)
            ->where('void_transactions.type', 'sell')
            ->where('void_transactions.status', 'final')
            ->select(
                'void_transactions.id',
                'void_transactions.ip_address',
                'void_transactions.mac_address',
                'void_transactions.transaction_date',
                'void_transactions.is_direct_sale',
                'void_transactions.invoice_no',
                'contacts.name as contact_name',
                'void_transactions.payment_status',
                'void_transactions.final_total',
                'void_transactions.tax_amount',
                'void_transactions.discount_amount',
                'void_transactions.discount_type',
                'void_transactions.total_before_tax',
                DB::raw('SUM(IF(tp.is_return = 1,-1*tp.amount,tp.amount)) as total_paid'),
                'bl.name as business_location',
                DB::raw('COUNT(SR.id) as return_exists'),
                DB::raw('(SELECT SUM(TP2.amount) FROM transaction_payments AS TP2 WHERE TP2.transaction_id=SR.id ) as return_paid'),
                DB::raw('COALESCE(SR.final_total, 0) as amount_return'),
                'SR.id as return_transaction_id'
            );

        // Apply filters
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('void_transactions.invoice_no', 'like', '%' . $this->search . '%')
                  ->orWhere('contacts.name', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereDate('void_transactions.transaction_date', '>=', $this->start_date)
                  ->whereDate('void_transactions.transaction_date', '<=', $this->end_date);
        }

        if (!empty($this->payment_status)) {
            $query->where('void_transactions.payment_status', $this->payment_status);
        }

        if (!empty($this->location_id)) {
            $query->where('void_transactions.location_id', $this->location_id);
        }

        if (!empty($this->customer_id)) {
            $query->where('contacts.id', $this->customer_id);
        }

        if ($this->is_direct_sale !== '') {
            if ($this->is_direct_sale == 0) {
                $query->where('void_transactions.is_direct_sale', 0)
                      ->whereNull('void_transactions.sub_type');
            }
        }

        if (!empty($this->commission_agent)) {
            $query->where('void_transactions.commission_agent', $this->commission_agent);
        }

        if (!empty($this->res_waiter_id)) {
            $query->where('void_transactions.res_waiter_id', $this->res_waiter_id);
        }

        if (!empty($this->sub_type)) {
            $query->where('void_transactions.sub_type', $this->sub_type);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $sells = $query->groupBy('void_transactions.id')
                      ->paginate($this->perPage);
        
        $business_locations = BusinessLocation::forDropdown($business_id, false);
        $customers = Contact::customersDropdown($business_id, false);

        return view('livewire.void-transaction-table', [
            'sells' => $sells,
            'business_locations' => $business_locations,
            'customers' => $customers,
            'is_woocommerce' => $is_woocommerce
        ]);
    }

} 
