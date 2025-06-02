<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\BusinessLocation;
use App\Models\Contact;
use App\Traits\WithSortingSearchPagination;
use App\Utils\TransactionUtil;
use App\Utils\BusinessUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UnitSellTable extends Component
{
    use WithPagination, WithSortingSearchPagination;
    // Define your filters
    public $start_date = '';
    public $end_date = '';
    public $payment_status = '';
    public $location_id = '';
    public $customer_id = '';
    public $is_direct_sale = '';
    public $commission_agent = '';
    public $res_waiter_id = '';
    public $sub_type = '';

    public function mount()
    {
        
        
        // $this->sortField = 'created_at'; // Different default sort field
        // $this->sortDirection = 'desc';   // Different default sort direction
        // $this->perPageOptions = [5, 10, 25]; // Different page size options
        $this->mountWithSortingSearchPagination();

        $this->filterConfig = [
            'start_date' => '',
        'end_date' => '',
        'payment_status' => '',
        'location_id' => '',
        'customer_id' => '',
        'is_direct_sale' => '',
        'commission_agent' => '',
        'res_waiter_id' => '',
        'sub_type' => '',
        ];
        

    }
    

   

    public function render()
    {
        $business_id = request()->session()->get('user.business_id');
        $macAddr = request()->session()->get('user.mac_address');
        $is_woocommerce = false;

        $query = Transaction::leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
            ->leftJoin('transaction_payments as tp', 'transactions.id', '=', 'tp.transaction_id')
            ->join('business_locations AS bl', 'transactions.location_id', '=', 'bl.id')
            ->leftJoin('transactions AS SR', 'transactions.id', '=', 'SR.return_parent_id')
            ->where('transactions.business_id', $business_id)
            ->where('transactions.mac_address', $macAddr)
            ->where('transactions.type', 'sell')
            ->where('transactions.status', 'final')
            ->select(
                'transactions.id',
                'transactions.ip_address',
                'transactions.mac_address',
                'transactions.transaction_date',
                'transactions.is_direct_sale',
                'transactions.invoice_no',
                'contacts.name as contact_name',
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
            );

        // Apply filters
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('transactions.invoice_no', 'like', '%' . $this->search . '%')
                  ->orWhere('contacts.name', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereDate('transactions.transaction_date', '>=', $this->start_date)
                  ->whereDate('transactions.transaction_date', '<=', $this->end_date);
        }

        if (!empty($this->payment_status)) {
            $query->where('transactions.payment_status', $this->payment_status);
        }

        if (!empty($this->location_id)) {
            $query->where('transactions.location_id', $this->location_id);
        }

        if (!empty($this->customer_id)) {
            $query->where('contacts.id', $this->customer_id);
        }

        if ($this->is_direct_sale !== '') {
            if ($this->is_direct_sale == 0) {
                $query->where('transactions.is_direct_sale', 0)
                      ->whereNull('transactions.sub_type');
            }
        }

        if (!empty($this->commission_agent)) {
            $query->where('transactions.commission_agent', $this->commission_agent);
        }

        if (!empty($this->res_waiter_id)) {
            $query->where('transactions.res_waiter_id', $this->res_waiter_id);
        }

        if (!empty($this->sub_type)) {
            $query->where('transactions.sub_type', $this->sub_type);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $sells = $query->groupBy('transactions.id')
                     ->paginate($this->perPage == -1 ? 9999 : $this->perPage);
        
        $business_locations = BusinessLocation::forDropdown($business_id, false);
        $customers = Contact::customersDropdown($business_id, false);

        return view('livewire.unit-sell-table', [
            'sells' => $sells,
            'business_locations' => $business_locations,
            'customers' => $customers,
            'is_woocommerce' => $is_woocommerce
        ]);
    }


} 
