<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\ExpenseCategory;
use App\Models\User;
use App\Models\BusinessLocation;
use Illuminate\Support\Facades\DB;

class ManageExpenses extends Component
{
    use WithPagination;

    public $expense_for;
    public $location_id;
    public $expense_category_id;
    public $start_date;
    public $end_date;

    public $categories = [];
    public $users = [];
    public $business_locations = [];

    public function mount()
    {
        $business_id = session()->get('user.business_id');
        $this->categories = ExpenseCategory::where('business_id', $business_id)->pluck('name', 'id')->toArray();
        $this->users = User::forDropdown($business_id, false, true, true);
        $this->business_locations = BusinessLocation::forDropdown($business_id, true);
    }

    public function updating($field)
    {
        $this->resetPage();
    }

    public function render()
    {
        $business_id = session()->get('user.business_id');

        $expenses = Transaction::leftJoin('expense_categories AS ec', 'transactions.expense_category_id', '=', 'ec.id')
            ->join('business_locations AS bl', 'transactions.location_id', '=', 'bl.id')
            ->leftJoin('users AS U', 'transactions.expense_for', '=', 'U.id')
            ->leftJoin('transaction_payments AS TP', 'transactions.id', '=', 'TP.transaction_id')
            ->where('transactions.business_id', $business_id)
            ->where('transactions.type', 'expense')
            ->select(
                'transactions.id',
                'transactions.document',
                'transaction_date',
                'ref_no',
                'ec.name as category',
                'payment_status',
                'additional_notes',
                'final_total',
                'bl.name as location_name',
                DB::raw("CONCAT(COALESCE(U.surname, ''),' ',COALESCE(U.first_name, ''),' ',COALESCE(U.last_name,'')) as expense_for"),
                DB::raw('SUM(TP.amount) as amount_paid')
            )
            ->groupBy('transactions.id');

        if (!empty($this->expense_for)) {
            $expenses->where('transactions.expense_for', $this->expense_for);
        }

        if (!empty($this->location_id)) {
            $expenses->where('transactions.location_id', $this->location_id);
        }

        if (!empty($this->expense_category_id)) {
            $expenses->where('transactions.expense_category_id', $this->expense_category_id);
        }

        if (!empty($this->start_date) && !empty($this->end_date)) {
            $expenses->whereDate('transaction_date', '>=', $this->start_date)
                ->whereDate('transaction_date', '<=', $this->end_date);
        }

        $permitted_locations = auth()->user()->permitted_locations();
        if ($permitted_locations != 'all') {
            $expenses->whereIn('transactions.location_id', $permitted_locations);
        }

        return view('livewire.manage-expenses', [
            'expenses' => $expenses->paginate(10),
        ]);
    }
}
