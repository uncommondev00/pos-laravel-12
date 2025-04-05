<?php

namespace App\Livewire;

use App\Models\BusinessLocation;
use App\Models\Contact;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Utils\ProductUtil;

class PurchaseTable extends Component
{
    use WithPagination;

    public $search = '';
    public $supplier_id;
    public $location_id;
    public $payment_status;
    public $status;
    public $start_date;
    public $end_date;
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'status' => ['except' => ''],
        'payment_status' => ['except' => ''],
    ];

    protected $listeners = ['refreshTable' => '$refresh'];

    protected $productUtil;

    public function mount(ProductUtil $productUtil)
    {
        if (!auth()->user()->can('purchase.view') && !auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }
        $this->productUtil = $productUtil;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function getBaseQuery()
    {
        $business_id = request()->session()->get('user.business_id');
        $permitted_locations = auth()->user()->permitted_locations();

        $query = Transaction::with(['contact:id,name', 'location:id,name'])
            ->select([
                'transactions.id',
                'transactions.document',
                'transactions.transaction_date',
                'transactions.ref_no',
                'transactions.contact_id',
                'transactions.location_id',
                'transactions.status',
                'transactions.payment_status',
                'transactions.final_total',
                DB::raw('COALESCE((
                    SELECT SUM(amount) 
                    FROM transaction_payments 
                    WHERE transaction_id = transactions.id
                ), 0) as amount_paid'),
                DB::raw('(
                    SELECT COUNT(id) 
                    FROM transactions as t 
                    WHERE t.return_parent_id = transactions.id
                ) as return_exists'),
                DB::raw('COALESCE((
                    SELECT t.final_total 
                    FROM transactions as t 
                    WHERE t.return_parent_id = transactions.id 
                    LIMIT 1
                ), 0) as amount_return'),
                DB::raw('COALESCE((
                    SELECT SUM(tp.amount) 
                    FROM transaction_payments as tp
                    JOIN transactions as t ON tp.transaction_id = t.id
                    WHERE t.return_parent_id = transactions.id
                ), 0) as return_paid')
            ])
            ->where('transactions.business_id', $business_id)
            ->where('transactions.type', 'purchase');

        if ($permitted_locations !== 'all') {
            $query->whereIn('transactions.location_id', $permitted_locations);
        }

        return $query;
    }

    public function render()
    {
        $cacheKey = $this->getCacheKey();
        $business_id = request()->session()->get('user.business_id');

        $purchases = Cache::remember($cacheKey, 300, function () {
            $query = $this->getBaseQuery();

            return $query->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('transactions.ref_no', 'like', '%' . $this->search . '%')
                        ->orWhereHas('contact', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
                ->when($this->supplier_id, function ($query) {
                    $query->where('contact_id', $this->supplier_id);
                })
                ->when($this->location_id, function ($query) {
                    $query->where('location_id', $this->location_id);
                })
                ->when($this->payment_status, function ($query) {
                    $query->where('payment_status', $this->payment_status);
                })
                ->when($this->status, function ($query) {
                    $query->where('status', $this->status);
                })
                ->when($this->start_date && $this->end_date, function ($query) {
                    $query->whereDate('transaction_date', '>=', $this->start_date)
                        ->whereDate('transaction_date', '<=', $this->end_date);
                })
                ->latest('transaction_date')
                ->paginate($this->perPage);
        });
        $business_locations = BusinessLocation::forDropdown($business_id);
        $suppliers = Contact::suppliersDropdown($business_id, false);
        $orderStatuses = $this->productUtil->orderStatuses();

        return view('livewire.purchase-table', [
            'purchases' => $purchases,
            'business_locations' => $business_locations,
            'suppliers' => $suppliers,
            'orderStatuses' => $orderStatuses
        ]);
    }

    private function getCacheKey(): string
    {
        $user_id = auth()->id();
        return sprintf(
            'purchases_table_%s_%s_%s_%s_%s_%s_%s_%s_%s_%d',
            $user_id,
            $this->search,
            $this->supplier_id ?? 'null',
            $this->location_id ?? 'null',
            $this->payment_status ?? 'null',
            $this->status ?? 'null',
            $this->start_date ?? 'null',
            $this->end_date ?? 'null',
            $this->page ?? 1,
            $this->perPage
        );
    }

    public function getPaymentDue($purchase)
    {
        if (!$purchase) return '';

        $due = $purchase->final_total - $purchase->amount_paid;
        $due_html = '<strong>' . __('lang_v1.purchase') . ':</strong> <span class="display_currency payment_due" data-currency_symbol="true" data-orig-value="' . $due . '">' . $due . '</span>';

        if ($purchase->return_exists) {
            $return_due = $purchase->amount_return - $purchase->return_paid;
            $due_html .= '<br><strong>' . __('lang_v1.purchase_return') . ':</strong> <a href="' . action("TransactionPaymentController@show", [$purchase->id]) . '" class="view_purchase_return_payment_modal no-print"><span class="display_currency purchase_return" data-currency_symbol="true" data-orig-value="' . $return_due . '">' . $return_due . '</span></a><span class="display_currency print_section" data-currency_symbol="true">' . $return_due . '</span>';
        }
        return $due_html;
    }

    public function getFooterTotals()
    {
        $business_id = request()->session()->get('user.business_id');

        return Cache::remember('purchase_footer_totals_' . $this->getCacheKey(), 300, function () use ($business_id) {
            $query = $this->getBaseQuery();

            return $query->select([
                DB::raw('COUNT(DISTINCT transactions.id) as total_count'),
                DB::raw('SUM(transactions.final_total) as total_amount'),
                DB::raw('SUM(
                CASE 
                    WHEN transactions.payment_status = "paid" THEN 1 
                    ELSE 0 
                END
            ) as total_paid'),
                DB::raw('SUM(
                CASE 
                    WHEN transactions.payment_status = "partial" THEN 1 
                    ELSE 0 
                END
            ) as total_partial'),
                DB::raw('SUM(
                CASE 
                    WHEN transactions.payment_status = "due" THEN 1 
                    ELSE 0 
                END
            ) as total_due'),
                DB::raw('SUM(
                CASE 
                    WHEN transactions.status = "received" THEN 1 
                    ELSE 0 
                END
            ) as total_received'),
                DB::raw('SUM(
                CASE 
                    WHEN transactions.status = "pending" THEN 1 
                    ELSE 0 
                END
            ) as total_pending'),
                DB::raw('SUM(
                CASE 
                    WHEN transactions.status = "ordered" THEN 1 
                    ELSE 0 
                END
            ) as total_ordered'),
                DB::raw('COALESCE(SUM(
                transactions.final_total - COALESCE((
                    SELECT SUM(amount) 
                    FROM transaction_payments 
                    WHERE transaction_id = transactions.id
                ), 0)
            ), 0) as total_due_amount'),
                DB::raw('COALESCE(SUM(
                CASE 
                    WHEN return_parent_id IS NOT NULL 
                    THEN final_total - COALESCE((
                        SELECT SUM(tp.amount) 
                        FROM transaction_payments as tp
                        WHERE tp.transaction_id = transactions.id
                    ), 0)
                    ELSE 0 
                END
            ), 0) as total_return_due_amount')
            ])
                ->when($this->supplier_id, function ($query) {
                    return $query->where('contact_id', $this->supplier_id);
                })
                ->when($this->location_id, function ($query) {
                    return $query->where('location_id', $this->location_id);
                })
                ->when($this->payment_status, function ($query) {
                    return $query->where('payment_status', $this->payment_status);
                })
                ->when($this->status, function ($query) {
                    return $query->where('status', $this->status);
                })
                ->when($this->start_date && $this->end_date, function ($query) {
                    return $query->whereDate('transaction_date', '>=', $this->start_date)
                        ->whereDate('transaction_date', '<=', $this->end_date);
                })
                ->first();
        });
    }
}
