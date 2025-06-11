<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Utils\Util;
use Illuminate\Support\Facades\Auth;

class DraftsTable extends Component
{
    use WithPagination;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public $search = '';
    public $perPage = 10;
    public $perPageOptions = [10, 25, 50, 100, -1];
    public $sortField = 'invoice_no';
    public $sortDirection = 'asc';
    public $is_quotation = 0;
    
    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'is_quotation' => ['except' => 0],
        'search' => ['except' => ''],
        'sortField' => ['except' => 'invoice_no'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {

        $this->search = request()->query('search', $this->search);
        $this->sortField = request()->query('sortField', $this->sortField);
        $this->sortDirection = request()->query('sortDirection', $this->sortDirection);
        $this->perPage = request()->query('perPage', $this->perPage);
        $this->is_quotation = request()->is_quotation ?? 0;
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

        $sells->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage == -1 ? 9999 : $this->perPage);

        return view('livewire.drafts-table', [
            'sells' => $sells->paginate($this->perPage),
            'util' => $util
        ]);
    }
}
