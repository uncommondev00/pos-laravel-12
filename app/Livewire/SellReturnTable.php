<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\BusinessLocation;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SellReturnTable extends Component
{
    public $search = '';
    public $location = '';
    public $customer = '';
    public $fromDate = '';
    public $toDate = '';
    public $perPage = 10;

    public function render()
    {
        $business_id = session('user.business_id');

        $query = Transaction::query()
            ->leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
            ->join('business_locations AS bl', 'transactions.location_id', '=', 'bl.id')
            ->join('transactions as T1', 'transactions.return_parent_id', '=', 'T1.id')
            ->leftJoin('transaction_payments AS TP', 'transactions.id', '=', 'TP.transaction_id')
            ->where('transactions.business_id', $business_id)
            ->where('transactions.type', 'sell_return')
            ->where('transactions.status', 'final')
            ->select([
                'transactions.id',
                'transactions.transaction_date',
                'transactions.invoice_no',
                'contacts.name as customer_name',
                'transactions.final_total',
                'transactions.payment_status',
                'bl.name as business_location',
                'T1.invoice_no as parent_sale',
                'T1.id as parent_sale_id',
                'transactions.contact_id',
                'transactions.location_id',
                DB::raw('SUM(TP.amount) as amount_paid')
            ])
            ->groupBy('transactions.id');

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('transactions.invoice_no', 'like', '%' . $this->search . '%')
                  ->orWhere('contacts.name', 'like', '%' . $this->search . '%');
            });
        }

        // Apply location filter
        if ($this->location) {
            $query->where('transactions.location_id', $this->location);
        }

        // Apply customer filter
        if ($this->customer) {
            $query->where('transactions.contact_id', $this->customer);
        }

        // Apply date filters
        if ($this->fromDate) {
            $query->whereDate('transactions.transaction_date', '>=', $this->fromDate);
        }
        if ($this->toDate) {
            $query->whereDate('transactions.transaction_date', '<=', $this->toDate);
        }

        // Handle permitted locations
        $permitted_locations = Auth::user()->permitted_locations();
        if ($permitted_locations != 'all') {
            $query->whereIn('transactions.location_id', $permitted_locations);
        }

        $sells = $query->paginate($this->perPage);
        $locations = BusinessLocation::forDropdown($business_id);
        $customers = Contact::customersDropdown($business_id);

        return view('livewire.sell-return-table', [
            'sells' => $sells,
            'locations' => $locations,
            'customers' => $customers
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLocation()
    {
        $this->resetPage();
    }

    public function updatingCustomer()
    {
        $this->resetPage();
    }

    public function updatingFromDate()
    {
        $this->resetPage();
    }

    public function updatingToDate()
    {
        $this->resetPage();
    }
}
