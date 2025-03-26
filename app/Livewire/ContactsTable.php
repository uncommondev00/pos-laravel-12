<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ContactsTable extends Component
{
    use WithPagination;

    public $search = ''; // Search input
    public $type = '';
    public $perPage = 5; // Items per page
    public $perPageOptions = [5, 10, 25, 50, 100]; // Dropdown options
    public $sortColumn = 'name'; // Default sort column
    public $sortDirection = 'asc'; // Default sort direction

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'type',
        'search',
        'perPage'
    ];

    public function updatingSearch(){
        $this->resetPage();
    }

    public function updatingPerPage(){
        $this->resetPage();
    }

    public function updatePerPage()
     {
         $this->resetPage();
     }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $type = request()->get('type');

        $types = ['supplier', 'customer'];

        if (empty($type) || !in_array($type, $types)) {
            return redirect()->back();
        }

        $business_id = request()->session()->get('user.business_id');
        if ($type == 'supplier') {
            if (!auth()->user()->can('supplier.view')) {
                abort(403, 'Unauthorized action.');
            }
    
            $contacts = Contact::leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                        ->where('contacts.business_id', $business_id)
                        ->onlySuppliers()
                        ->select(['contacts.contact_id', 'supplier_business_name', 'name', 'mobile',
                            'contacts.type', 'contacts.id',
                            DB::raw("SUM(IF(t.type = 'purchase', final_total, 0)) as total_purchase"),
                            DB::raw("SUM(IF(t.type = 'purchase', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_paid"),
                            DB::raw("SUM(IF(t.type = 'purchase_return', final_total, 0)) as total_purchase_return"),
                            DB::raw("SUM(IF(t.type = 'purchase_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_return_paid"),
                            DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
                            DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as opening_balance_paid")
                            ])
                        ->groupBy('contacts.id');

            $this->search = trim($this->search);

            if (!empty($this->search)) {
                $contacts->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                });
            }
    
            $contacts = $contacts->orderBy($this->sortColumn, $this->sortDirection)
                ->paginate($this->perPage);

        } elseif ($type == 'customer') {
            if (!auth()->user()->can('customer.view')) {
                abort(403, 'Unauthorized action.');
            }

            $contacts = Contact::leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                    ->leftjoin('customer_groups AS cg', 'contacts.customer_group_id', '=', 'cg.id')
                    ->where('contacts.business_id', $business_id)
                    ->onlyCustomers()
                    ->select(['contacts.contact_id', 'contacts.name', 'cg.name as customer_group', 'city', 'state', 'country', 'landmark', 'mobile', 'contacts.id', 'is_default','points_value','points_status',
                        DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                        DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as invoice_received"),
                        DB::raw("SUM(IF(t.type = 'sell_return', final_total, 0)) as total_sell_return"),
                        DB::raw("SUM(IF(t.type = 'sell_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as sell_return_paid"),
                        DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
                        DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as opening_balance_paid")
                        ])
                    ->groupBy('contacts.id');

            $this->search = trim($this->search);

            if (!empty($this->search)) {
                $contacts->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                });
            }
    
            $contacts = $contacts->orderBy($this->sortColumn, $this->sortDirection)
                ->paginate($this->perPage);
        } else {
            $contacts = [];
        }
        

        return view('livewire.contacts-table', compact('contacts', 'type'));
    }
}
