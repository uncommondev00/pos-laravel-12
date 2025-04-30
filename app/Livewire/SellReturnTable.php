<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\BusinessLocation;
use App\Models\Contact;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SellReturnTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $perPageOptions = [10, 25, 50, 100, -1];
    public $sortField = 'invoice_no';
    public $sortDirection = 'asc';
    
    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'invoice_no'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        if (!auth()->user()->can('product.create')) {
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

        // Handle permitted locations
        $permitted_locations = Auth::user()->permitted_locations();
        if ($permitted_locations != 'all') {
            $query->whereIn('transactions.location_id', $permitted_locations);
        }

        $sells = $query->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage == -1 ? 9999 : $this->perPage);
        $locations = BusinessLocation::forDropdown($business_id);
        $customers = Contact::customersDropdown($business_id);

        return view('livewire.sell-return-table', [
            'sells' => $sells,
            'locations' => $locations,
            'customers' => $customers
        ]);
    }
}
