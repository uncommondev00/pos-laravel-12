<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Utils\TransactionUtil;
use App\Utils\BusinessUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StockAdjustmentTable extends Component
{
    use WithPagination;

    public $search = '';
    public $start_date = '';
    public $end_date = '';
    public $location_id = '';
    public $sortField = 'transaction_date';
    public $sortDirection = 'desc';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'location_id' => ['except' => ''],
        'sortField' => ['except' => 'transaction_date'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        if (!auth()->user()->can('purchase.view') && !auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }

        $this->search = request()->get('search', '');
        $this->start_date = request()->get('start_date', '');
        $this->end_date = request()->get('end_date', '');
        $this->location_id = request()->get('location_id', '');
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

        $query = Transaction::join(
            'business_locations AS BL',
            'transactions.location_id',
            '=',
            'BL.id'
        )
        ->where('transactions.business_id', $business_id)
        ->where('transactions.type', 'stock_adjustment')
        ->select(
            'transactions.id',
            'transaction_date',
            'ref_no',
            'BL.name as location_name',
            'adjustment_type',
            'final_total',
            'total_amount_recovered',
            'additional_notes'
        );

        $permitted_locations = auth()->user()->permitted_locations();
        if ($permitted_locations != 'all') {
            $query->whereIn('transactions.location_id', $permitted_locations);
        }

        $hide = '';
        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereBetween(DB::raw('date(transaction_date)'), [$this->start_date, $this->end_date]);
            $hide = 'hide';
        }

        if (!empty($this->location_id)) {
            $query->where('transactions.location_id', $this->location_id);
        }

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('transactions.ref_no', 'like', '%' . $this->search . '%')
                  ->orWhere('BL.name', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $stock_adjustments = $query->paginate($this->perPage);

        return view('livewire.stock-adjustment-table', [
            'stock_adjustments' => $stock_adjustments,
            'hide' => $hide
        ]);
    }
}
