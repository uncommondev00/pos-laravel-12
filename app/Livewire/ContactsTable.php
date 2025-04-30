<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Traits\WithSortingSearchPagination;

class ContactsTable extends Component
{
    use WithPagination, WithSortingSearchPagination;

    public $type = '';
    public $totalDue = 0;
    public $totalReturnDue = 0;


    public function mount()
    {
        $this->mountWithSortingSearchPagination();
        $this->filterConfig = [
            'type' => '',
        ];
    }

    public function calculateTotals($contacts)
    {
        if ($this->type == 'supplier') {
            $this->totalDue = $contacts->sum(function ($contact) {
                return ($contact->total_purchase + $contact->opening_balance) -
                    ($contact->purchase_paid + $contact->purchase_return + $contact->opening_balance_paid);
            });

            $this->totalReturnDue = $contacts->sum(function ($contact) {
                return $contact->purchase_return - $contact->purchase_return_paid;
            });
        } else {
            $this->totalDue = $contacts->sum(function ($contact) {
                return $contact->total_invoice - $contact->invoice_received;
            });

            $this->totalReturnDue = $contacts->sum(function ($contact) {
                return $contact->total_sell_return - $contact->sell_return_paid;
            });
        }
    }

    public function render()
    {
        $type = $this->type ?: request()->get('type');
        $types = ['supplier', 'customer'];
        $contacts = [];

        if (empty($type) || !in_array($type, $types)) {
            return view('livewire.contacts-table', compact('contacts', 'type'));
        }

        $business_id = request()->session()->get('user.business_id');

        if ($type == 'supplier') {
            if (!Auth::user()->can('supplier.view')) {
                abort(403, 'Unauthorized action.');
            }

            $contacts = Contact::leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                ->where('contacts.business_id', $business_id)
                ->onlySuppliers()
                ->select([
                    'contacts.contact_id',
                    'supplier_business_name',
                    'name',
                    'mobile',
                    'contacts.type',
                    'contacts.id',
                    DB::raw("SUM(IF(t.type = 'purchase', final_total, 0)) as total_purchase"),
                    DB::raw("SUM(IF(t.type = 'purchase', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_paid"),
                    DB::raw("SUM(IF(t.type = 'purchase_return', final_total, 0)) as total_purchase_return"),
                    DB::raw("SUM(IF(t.type = 'purchase_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_return_paid"),
                    DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
                    DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as opening_balance_paid")
                ])
                ->groupBy('contacts.id');
        } elseif ($type == 'customer') {
            if (!Auth::user()->can('customer.view')) {
                abort(403, 'Unauthorized action.');
            }

            $contacts = Contact::leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                ->leftjoin('customer_groups AS cg', 'contacts.customer_group_id', '=', 'cg.id')
                ->where('contacts.business_id', $business_id)
                ->onlyCustomers()
                ->select([
                    'contacts.contact_id',
                    'contacts.name',
                    'cg.name as customer_group',
                    'city',
                    'state',
                    'country',
                    'landmark',
                    'mobile',
                    'contacts.id',
                    'is_default',
                    'points_value',
                    'points_status',
                    DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                    DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as invoice_received"),
                    DB::raw("SUM(IF(t.type = 'sell_return', final_total, 0)) as total_sell_return"),
                    DB::raw("SUM(IF(t.type = 'sell_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as sell_return_paid"),
                    DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
                    DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as opening_balance_paid")
                ])
                ->groupBy('contacts.id');
        }

        if (!empty($this->search)) {
            $contacts->where(function ($query) {
                $query->where('contacts.name', 'like', '%' . trim($this->search) . '%');
            });
        }

        $contacts = $contacts->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage == -1 ? 9999 : $this->perPage);

        $this->calculateTotals($contacts);


        return view('livewire.contacts-table', compact('contacts', 'type'));
    }
}
