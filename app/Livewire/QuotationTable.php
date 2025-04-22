<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Utils\Util;
use Illuminate\Support\Facades\Auth;


class QuotationTable extends Component
{
    use WithPagination;

    public $search = '';
    public $start_date = '';
    public $end_date = '';
    public $is_quotation = 1;
    public $perPage = 10;
    public $sortField = 'transaction_date';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'is_quotation' => ['except' => 1],
        'sortField' => ['except' => 'transaction_date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->start_date = request()->start_date ?? '';
        $this->end_date = request()->end_date ?? '';
        $this->is_quotation = 1;
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
        $util = new Util();

        $sells = Transaction::leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
            ->join(
                'business_locations AS bl',
                'transactions.location_id',
                '=',
                'bl.id'
            )
            ->where('transactions.business_id', $business_id)
            ->where('transactions.type', 'sell')
            ->where('transactions.status', 'draft')
            ->where('is_quotation', $this->is_quotation)
            ->select(
                'transactions.id',
                'transaction_date',
                'invoice_no',
                'contacts.name as contact_name',
                'bl.name as business_location',
                'is_direct_sale'
            );

        $permitted_locations = auth()->user()->permitted_locations();
        if ($permitted_locations != 'all') {
            $sells->whereIn('transactions.location_id', $permitted_locations);
        }

        if (!empty($this->start_date) && !empty($this->end_date)) {
            $sells->whereDate('transaction_date', '>=', $this->start_date)
                ->whereDate('transaction_date', '<=', $this->end_date);
        }

        if (!empty($this->search)) {
            $sells->where(function ($query) {
                $query->where('invoice_no', 'like', '%' . $this->search . '%')
                    ->orWhere('contacts.name', 'like', '%' . $this->search . '%')
                    ->orWhere('bl.name', 'like', '%' . $this->search . '%');
            });
        }

        $sells->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.quotation-table', [
            'sells' => $sells->paginate($this->perPage),
            'util' => $util
        ]);
    }
}
