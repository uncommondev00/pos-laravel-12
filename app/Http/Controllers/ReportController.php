<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Charts;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Utils\TransactionUtil;
use App\Utils\ProductUtil;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Brands;
use App\Models\BusinessLocation;
use App\Models\ExpenseCategory;
use App\Models\CashRegister;
use App\Models\User;
use App\Models\PurchaseLine;
use App\Models\Transaction;
use App\Models\CustomerGroup;
use App\Models\TransactionSellLine;
use App\Models\TransactionPayment;
use App\Models\Restaurant\ResTable;
use App\Models\SellingPriceGroup;
use App\Models\Variation;
use App\Models\VariationLocationDetails;
use App\Models\XRead;
use App\Models\ZReading;
use App\Services\StockReportService;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $transactionUtil;
    protected $productUtil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TransactionUtil $transactionUtil, ProductUtil $productUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->productUtil = $productUtil;
    }

    /**
     * Shows profit\loss of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getProfitLoss(Request $request)
    {
        if (!auth()->user()->can('profit_loss_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            $location_id = $request->get('location_id');

            //For Opening stock date should be 1 day before
            $day_before_start_date = Carbon::createFromFormat('Y-m-d', $start_date)->subDay()->format('Y-m-d');
            //Get Opening stock
            $opening_stock = $this->transactionUtil->getOpeningClosingStock($business_id, $day_before_start_date, $location_id, true);

            //Get Closing stock
            $closing_stock = $this->transactionUtil->getOpeningClosingStock(
                $business_id,
                $end_date,
                $location_id
            );

            //Get Purchase details
            $purchase_details = $this->transactionUtil->getPurchaseTotals(
                $business_id,
                $start_date,
                $end_date,
                $location_id
            );

            //Get Sell details
            $sell_details = $this->transactionUtil->getSellTotals(
                $business_id,
                $start_date,
                $end_date,
                $location_id
            );

            $transaction_types = [
                'purchase_return',
                'sell_return',
                'expense',
                'stock_adjustment',
                'sell_transfer',
                'purchase',
                'sell'
            ];

            $transaction_totals = $this->transactionUtil->getTransactionTotals(
                $business_id,
                $transaction_types,
                $start_date,
                $end_date,
                $location_id
            );

            $total_transfer_shipping_charges = $transaction_totals['total_transfer_shipping_charges'];

            //Add total sell shipping charges to $total_transfer_shipping_charges
            if (!empty($sell_details['total_shipping_charges'])) {
                $total_transfer_shipping_charges += $sell_details['total_shipping_charges'];
            }
            //Add total purchase shipping charges to $total_transfer_shipping_charges
            if (!empty($purchase_details['total_shipping_charges'])) {
                $total_transfer_shipping_charges += $purchase_details['total_shipping_charges'];
            }

            //Discounts
            $total_purchase_discount = $transaction_totals['total_purchase_discount'];
            $total_sell_discount = $transaction_totals['total_sell_discount'];

            $data['opening_stock'] = !empty($opening_stock) ? $opening_stock : 0;
            $data['closing_stock'] = !empty($closing_stock) ? $closing_stock : 0;
            $data['total_purchase'] = !empty($purchase_details['total_purchase_exc_tax']) ? $purchase_details['total_purchase_exc_tax'] : 0;
            $data['total_sell'] = !empty($sell_details['total_sell_exc_tax']) ? $sell_details['total_sell_exc_tax'] : 0;
            $data['total_expense'] =  $transaction_totals['total_expense'];

            $data['total_adjustment'] = $transaction_totals['total_adjustment'];

            $data['total_recovered'] = $transaction_totals['total_recovered'];

            $data['total_transfer_shipping_charges'] = $total_transfer_shipping_charges;

            $data['total_purchase_discount'] = !empty($total_purchase_discount) ? $total_purchase_discount : 0;
            $data['total_sell_discount'] = !empty($total_sell_discount) ? $total_sell_discount : 0;

            $data['total_purchase_return'] = $transaction_totals['total_purchase_return_exc_tax'];

            $data['total_sell_return'] = $transaction_totals['total_sell_return_exc_tax'];

            $data['net_profit'] = $data['total_sell'] + $data['closing_stock'] -
                $data['total_purchase'] - $data['total_sell_discount'] -
                $data['opening_stock'] - $data['total_expense'] -
                $data['total_adjustment'] + $data['total_recovered'] -
                $data['total_transfer_shipping_charges'] + $data['total_purchase_discount']
                + $data['total_purchase_return'] - $data['total_sell_return'];
            return $data;
        }

        $business_locations = BusinessLocation::forDropdown($business_id, true);
        return view('report.profit_loss', compact('business_locations'));
    }

    /**
     * Shows product report of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseSell(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            $location_id = $request->get('location_id');

            $purchase_details = $this->transactionUtil->getPurchaseTotals($business_id, $start_date, $end_date, $location_id);

            $sell_details = $this->transactionUtil->getSellTotals(
                $business_id,
                $start_date,
                $end_date,
                $location_id
            );

            $transaction_types = [
                'purchase_return',
                'sell_return'
            ];

            $transaction_totals = $this->transactionUtil->getTransactionTotals(
                $business_id,
                $transaction_types,
                $start_date,
                $end_date,
                $location_id
            );

            $total_purchase_return_inc_tax = $transaction_totals['total_purchase_return_inc_tax'];
            $total_sell_return_inc_tax = $transaction_totals['total_sell_return_inc_tax'];

            $difference = [
                'total' => $sell_details['total_sell_inc_tax'] + $total_sell_return_inc_tax - $purchase_details['total_purchase_inc_tax'] - $total_purchase_return_inc_tax,
                'due' => $sell_details['invoice_due'] - $purchase_details['purchase_due']
            ];

            return [
                'purchase' => $purchase_details,
                'sell' => $sell_details,
                'total_purchase_return' => $total_purchase_return_inc_tax,
                'total_sell_return' => $total_sell_return_inc_tax,
                'difference' => $difference
            ];
        }

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.purchase_sell')
            ->with(compact('business_locations'));
    }

    /**
     * Shows product report of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseReport(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        if ($request->ajax()) {
            $query = PurchaseLine::join(
                'transactions as t',
                'purchase_lines.transaction_id',
                '=',
                't.id'
            )
                ->join('products as p', 'purchase_lines.product_id', '=', 'p.id')
                ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
                ->where('t.business_id', $business_id)
                ->whereIn('t.type', ['opening_stock', 'purchase'])
                ->select(
                    'p.name as product_name',
                    'p.type as product_type',
                    't.id as transaction_id',
                    'u.short_name as unit',
                    'purchase_lines.purchase_price as unit_price',
                    DB::raw('SUM((purchase_lines.quantity)) as opening_qty'),
                    DB::raw('SUM((purchase_lines.quantity_sold)) as sold_qty')
                )
                ->groupBy('p.id');

            return Datatables::of($query)
                ->editColumn('product_name', function ($row) {
                    $product_name = $row->product_name;
                    if ($row->product_type == 'variable') {
                        $product_name .= ' - ' . $row->product_variation . ' - ' . $row->variation_name;
                    }

                    return $product_name;
                })
                ->editColumn('opening_qty', function ($row) {
                    $t_qty = (float)$row->opening_qty - (float)$row->sold_qty;
                    return '<span data-is_quantity="true" class="display_currency opening_qty" data-currency_symbol=false data-orig-value="' . $t_qty . '" data-unit="' . $row->unit . '" >' . $t_qty . '</span> ' . $row->unit;
                })
                ->editColumn('unit_price', function ($row) {

                    return '<span class="display_currency" data-currency_symbol=true data-orig-value="' . (float)$row->unit_price . '" >' . (float) $row->unit_price . '</span> ';
                })
                ->editColumn('total', function ($row) {
                    $t_qty = (float)$row->opening_qty - (float)$row->sold_qty;
                    return '<span class="display_currency total" data-currency_symbol=true data-orig-value="' . (float)$row->unit_price * $t_qty . '" >' . (float) $row->unit_price * $t_qty . '</span> ';
                })
                ->rawColumns(['opening_qty', 'unit_price', 'total'])
                ->make(true);
        }

        //$business_locations = BusinessLocation::forDropdown($business_id);
        //$suppliers = Contact::suppliersDropdown($business_id);

        return view('report.purchase_report');
    }

    /**
     * Shows report for Supplier
     *
     * @return \Illuminate\Http\Response
     */
    public function getCustomerSuppliers(Request $request)
    {
        if (!auth()->user()->can('contacts_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $contacts = Contact::where('contacts.business_id', $business_id)
                ->join('transactions AS t', 'contacts.id', '=', 't.contact_id')
                ->groupBy('contacts.id')
                ->select(
                    DB::raw("SUM(IF(t.type = 'purchase', final_total, 0)) as total_purchase"),
                    DB::raw("SUM(IF(t.type = 'purchase_return', final_total, 0)) as total_purchase_return"),
                    DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                    DB::raw("SUM(IF(t.type = 'purchase', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_paid"),
                    DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as invoice_received"),
                    DB::raw("SUM(IF(t.type = 'sell_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as sell_return_paid"),
                    DB::raw("SUM(IF(t.type = 'purchase_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_return_received"),
                    DB::raw("SUM(IF(t.type = 'sell_return', final_total, 0)) as total_sell_return"),
                    DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
                    DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as opening_balance_paid"),
                    'contacts.supplier_business_name',
                    'contacts.name',
                    'contacts.id'
                );
            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $contacts->whereIn('t.location_id', $permitted_locations);
            }
            return Datatables::of($contacts)
                ->editColumn('name', function ($row) {
                    $name = $row->name;
                    if (!empty($row->supplier_business_name)) {
                        $name .= ', ' . $row->supplier_business_name;
                    }
                    return '<a href="' . route('contacts.show', [$row->id]) . '" target="_blank" class="no-print">' .
                        $name .
                        '</a><span class="print_section">' . $name . '</span>';
                })
                ->editColumn('total_purchase', function ($row) {
                    return '<span class="display_currency total_purchase" data-orig-value="' . $row->total_purchase . '" data-currency_symbol = true>' . $row->total_purchase . '</span>';
                })
                ->editColumn('total_purchase_return', function ($row) {
                    return '<span class="display_currency total_purchase_return" data-orig-value="' . $row->total_purchase_return . '" data-currency_symbol = true>' . $row->total_purchase_return . '</span>';
                })
                ->editColumn('total_sell_return', function ($row) {
                    return '<span class="display_currency total_sell_return" data-orig-value="' . $row->total_sell_return . '" data-currency_symbol = true>' . $row->total_sell_return . '</span>';
                })
                ->editColumn('total_invoice', function ($row) {
                    return '<span class="display_currency total_invoice" data-orig-value="' . $row->total_invoice . '" data-currency_symbol = true>' . $row->total_invoice . '</span>';
                })
                ->addColumn('due', function ($row) {
                    $due = ($row->total_invoice - $row->invoice_received - $row->total_sell_return + $row->sell_return_paid) - ($row->total_purchase - $row->total_purchase_return + $row->purchase_return_received - $row->purchase_paid) + ($row->opening_balance - $row->opening_balance_paid);

                    return '<span class="display_currency total_due" data-orig-value="' . $due . '" data-currency_symbol=true data-highlight=true>' . $due . '</span>';
                })
                ->addColumn(
                    'opening_balance_due',
                    '<span class="display_currency opening_balance_due" data-currency_symbol=true data-orig-value="{{$opening_balance - $opening_balance_paid}}">{{$opening_balance - $opening_balance_paid}}</span>'
                )
                ->removeColumn('supplier_business_name')
                ->removeColumn('invoice_received')
                ->removeColumn('purchase_paid')
                ->removeColumn('id')
                ->rawColumns(['total_purchase', 'total_invoice', 'due', 'name', 'total_purchase_return', 'total_sell_return', 'opening_balance_due'])
                ->make(true);
        }

        return view('report.contact');
    }

    /**
     * Shows product stock report
     *
     * @return \Illuminate\Http\Response
     */
    // public function getStockReport(Request $request)
    // {
    //     if (!auth()->user()->can('stock_report.view')) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     $business_id = $request->session()->get('user.business_id');

    //     $selling_price_groups = SellingPriceGroup::where('business_id', $business_id)
    //         ->get();
    //     $allowed_selling_price_group = false;
    //     foreach ($selling_price_groups as $selling_price_group) {
    //         if (auth()->user()->can('selling_price_group.' . $selling_price_group->id)) {
    //             $allowed_selling_price_group = true;
    //             break;
    //         }
    //     }

    //     //Return the details in ajax call
    //     if ($request->ajax()) {
    //         $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
    //             ->join('units', 'p.unit_id', '=', 'units.id')
    //             ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
    //             ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
    //             ->where('p.business_id', $business_id)
    //             ->whereIn('p.type', ['single', 'variable']);

    //         $permitted_locations = auth()->user()->permitted_locations();
    //         $location_filter = '';

    //         if ($permitted_locations != 'all') {
    //             $query->whereIn('vld.location_id', $permitted_locations);

    //             $locations_imploded = implode(', ', $permitted_locations);
    //             $location_filter .= "AND transactions.location_id IN ($locations_imploded) ";
    //         }

    //         if (!empty($request->input('location_id'))) {
    //             $location_id = $request->input('location_id');

    //             $query->where('vld.location_id', $location_id);

    //             $location_filter .= "AND transactions.location_id=$location_id";
    //         }

    //         if (!empty($request->input('category_id'))) {
    //             $query->where('p.category_id', $request->input('category_id'));
    //         }
    //         if (!empty($request->input('sub_category_id'))) {
    //             $query->where('p.sub_category_id', $request->input('sub_category_id'));
    //         }
    //         if (!empty($request->input('brand_id'))) {
    //             $query->where('p.brand_id', $request->input('brand_id'));
    //         }
    //         if (!empty($request->input('unit_id'))) {
    //             $query->where('p.unit_id', $request->input('unit_id'));
    //         }

    //         //TODO::Check if result is correct after changing LEFT JOIN to INNER JOIN

    //         $products = $query->select(
    //             // DB::raw("(SELECT SUM(quantity) FROM transaction_sell_lines LEFT JOIN transactions ON transaction_sell_lines.transaction_id=transactions.id WHERE transactions.status='final' $location_filter AND
    //             //     transaction_sell_lines.product_id=products.id) as total_sold"),

    //             DB::raw("(SELECT SUM(IF(transactions.type='sell', TSL.quantity - TSL.quantity_returned , -1* TPL.quantity) ) FROM transactions
    //                     JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id

    //                     LEFT JOIN purchase_lines AS TPL ON transactions.id=TPL.transaction_id

    //                     WHERE transactions.status='final' AND transactions.type='sell' $location_filter
    //                     AND (TSL.variation_id=variations.id OR TPL.variation_id=variations.id)) as total_sold"),
    //             DB::raw("(SELECT SUM(IF(transactions.type='sell_transfer', TSL.quantity, 0) ) FROM transactions
    //                     JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id
    //                     WHERE transactions.status='final' AND transactions.type='sell_transfer' $location_filter
    //                     AND (TSL.variation_id=variations.id)) as total_transfered"),
    //             DB::raw("(SELECT SUM(IF(transactions.type='stock_adjustment', SAL.quantity, 0) ) FROM transactions
    //                     JOIN stock_adjustment_lines AS SAL ON transactions.id=SAL.transaction_id
    //                     WHERE transactions.status='received' AND transactions.type='stock_adjustment' $location_filter
    //                     AND (SAL.variation_id=variations.id)) as total_adjusted"),
    //             DB::raw("SUM(vld.qty_available) as stock"),
    //             'variations.sub_sku as sku',
    //             'p.name as product',
    //             'p.type',
    //             'p.id as product_id',
    //             'units.short_name as unit',
    //             'p.enable_stock as enable_stock',
    //             'variations.sell_price_inc_tax as unit_price',
    //             'pv.name as product_variation',
    //             'variations.name as variation_name'
    //         )->groupBy('variations.id');

    //         return Datatables::of($products)
    //             ->editColumn('stock', function ($row) {
    //                 if ($row->enable_stock) {
    //                     $stock = $row->stock ? $row->stock : 0;
    //                     return  '<span data-is_quantity="true" class="current_stock display_currency" data-orig-value="' . (float)$stock . '" data-unit="' . $row->unit . '" data-currency_symbol=false > ' . (float)$stock . '</span>' . ' ' . $row->unit;
    //                 } else {
    //                     return 'N/A';
    //                 }
    //             })
    //             ->editColumn('product', function ($row) {
    //                 $name = $row->product;
    //                 if ($row->type == 'variable') {
    //                     $name .= ' - ' . $row->product_variation . '-' . $row->variation_name;
    //                 }
    //                 return $name;
    //             })
    //             ->editColumn('total_sold', function ($row) {
    //                 $total_sold = 0;
    //                 if ($row->total_sold) {
    //                     $total_sold =  (float)$row->total_sold;
    //                 }

    //                 return '<span data-is_quantity="true" class="display_currency total_sold" data-currency_symbol=false data-orig-value="' . $total_sold . '" data-unit="' . $row->unit . '" >' . $total_sold . '</span> ' . $row->unit;
    //             })
    //             ->editColumn('total_transfered', function ($row) {
    //                 $total_transfered = 0;
    //                 if ($row->total_transfered) {
    //                     $total_transfered =  (float)$row->total_transfered;
    //                 }

    //                 return '<span data-is_quantity="true" class="display_currency total_transfered" data-currency_symbol=false data-orig-value="' . $total_transfered . '" data-unit="' . $row->unit . '" >' . $total_transfered . '</span> ' . $row->unit;
    //             })
    //             ->editColumn('total_adjusted', function ($row) {
    //                 $total_adjusted = 0;
    //                 if ($row->total_adjusted) {
    //                     $total_adjusted =  (float)$row->total_adjusted;
    //                 }

    //                 return '<span data-is_quantity="true" class="display_currency total_adjusted" data-currency_symbol=false  data-orig-value="' . $total_adjusted . '" data-unit="' . $row->unit . '" >' . $total_adjusted . '</span> ' . $row->unit;
    //             })
    //             ->editColumn('unit_price', function ($row) use ($allowed_selling_price_group) {
    //                 $html = '';
    //                 //if (auth()->user()->can('access_default_selling_price')) {
    //                 $html .= '<span class="display_currency" data-currency_symbol=true >'
    //                     . $row->unit_price . '</span>';
    //                 //}

    //                 if ($allowed_selling_price_group) {
    //                     $html .= ' <button type="button" class="btn btn-primary btn-xs btn-modal no-print" data-container=".view_modal" data-href="' . route('products.viewGroupPrice', [$row->product_id]) . '">' . __('lang_v1.view_group_prices') . '</button>';
    //                 }

    //                 return $html;
    //             })
    //             ->removeColumn('enable_stock')
    //             ->removeColumn('unit')
    //             ->removeColumn('id')
    //             ->rawColumns([
    //                 'unit_price',
    //                 'total_transfered',
    //                 'total_sold',
    //                 'total_adjusted',
    //                 'stock'
    //             ])
    //             ->make(true);
    //     }

    //     $categories = Category::where('business_id', $business_id)
    //         ->where('parent_id', 0)
    //         ->pluck('name', 'id');
    //     $brands = Brands::where('business_id', $business_id)
    //         ->pluck('name', 'id');
    //     $units = Unit::where('business_id', $business_id)
    //         ->pluck('short_name', 'id');
    //     $business_locations = BusinessLocation::forDropdown($business_id, true);

    //     return view('report.stock_report')
    //         ->with(compact('categories', 'brands', 'units', 'business_locations'));
    // }

    public function getStockReport(Request $request)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $success = ((new StockReportService())->regenerateCache());

        $business_id = $request->session()->get('user.business_id');

        $allowed_selling_price_group = SellingPriceGroup::where('business_id', $business_id)
            ->get()
            ->filter(fn($group) => auth()->user()->can('selling_price_group.' . $group->id))
            ->isNotEmpty();

        if ($request->ajax()) {
            $permitted_locations = auth()->user()->permitted_locations();
            $location_filter = [];

            if ($permitted_locations !== 'all') {
                $location_filter[] = ['vld.location_id', $permitted_locations];
            }

            if ($request->filled('location_id')) {
                $location_filter[] = ['vld.location_id', $request->input('location_id')];
            }

            $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
                ->join('units', 'p.unit_id', '=', 'units.id')
                ->leftJoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                ->leftJoin('cached_variation_sales as cvs', 'variations.id', '=', 'cvs.variation_id')
                ->where('p.business_id', $business_id)
                ->whereIn('p.type', ['single', 'variable'])
                ->when($request->filled('category_id'), fn($q) => $q->where('p.category_id', $request->input('category_id')))
                ->when($request->filled('sub_category_id'), fn($q) => $q->where('p.sub_category_id', $request->input('sub_category_id')))
                ->when($request->filled('brand_id'), fn($q) => $q->where('p.brand_id', $request->input('brand_id')))
                ->when($request->filled('unit_id'), fn($q) => $q->where('p.unit_id', $request->input('unit_id')))
                ->when(!empty($location_filter), function ($q) use ($location_filter) {
                    foreach ($location_filter as $filter) {
                        $q->where($filter[0], $filter[1]);
                    }
                });

            $products = $query->select([
                'variations.id',
                DB::raw('COALESCE(SUM(vld.qty_available), 0) as stock'),
                'variations.sub_sku as sku',
                'p.name as product',
                'p.type',
                'p.id as product_id',
                'units.short_name as unit',
                'p.enable_stock',
                'variations.sell_price_inc_tax as unit_price',
                'pv.name as product_variation',
                'variations.name as variation_name',
                DB::raw('COALESCE(cvs.total_sold, 0) as total_sold'),
                DB::raw('COALESCE(cvs.total_transfered, 0) as total_transfered'),
                DB::raw('COALESCE(cvs.total_adjusted, 0) as total_adjusted')
            ])
                ->groupBy('variations.id');

            return Datatables::of($products)
                ->editColumn(
                    'stock',
                    fn($row) =>
                    $row->enable_stock
                        ? '<span data-is_quantity="true" class="current_stock display_currency" data-orig-value="' . (float)$row->stock . '" data-unit="' . $row->unit . '" data-currency_symbol=false>' . (float)$row->stock . '</span> ' . $row->unit
                        : 'N/A'
                )
                ->editColumn(
                    'product',
                    fn($row) =>
                    $row->type === 'variable'
                        ? "{$row->product} - {$row->product_variation}-{$row->variation_name}"
                        : $row->product
                )
                ->editColumn(
                    'total_sold',
                    fn($row) =>
                    '<span data-is_quantity="true" class="display_currency total_sold" data-currency_symbol=false data-orig-value="' . (float)$row->total_sold . '" data-unit="' . $row->unit . '">' . (float)$row->total_sold . '</span> ' . $row->unit
                )
                ->editColumn(
                    'total_transfered',
                    fn($row) =>
                    '<span data-is_quantity="true" class="display_currency total_transfered" data-currency_symbol=false data-orig-value="' . (float)$row->total_transfered . '" data-unit="' . $row->unit . '">' . (float)$row->total_transfered . '</span> ' . $row->unit
                )
                ->editColumn(
                    'total_adjusted',
                    fn($row) =>
                    '<span data-is_quantity="true" class="display_currency total_adjusted" data-currency_symbol=false data-orig-value="' . (float)$row->total_adjusted . '" data-unit="' . $row->unit . '">' . (float)$row->total_adjusted . '</span> ' . $row->unit
                )
                ->editColumn('unit_price', function ($row) use ($allowed_selling_price_group) {
                    $html = '<span class="display_currency" data-currency_symbol=true>' . $row->unit_price . '</span>';
                    if ($allowed_selling_price_group) {
                        $html .= ' <button type="button" class="btn btn-primary btn-xs btn-modal no-print" data-container=".view_modal" data-href="' . route('products.viewGroupPrice', [$row->product_id]) . '">' . __('lang_v1.view_group_prices') . '</button>';
                    }
                    return $html;
                })
                ->removeColumn('enable_stock')
                ->removeColumn('unit')
                ->removeColumn('id')
                ->rawColumns(['unit_price', 'total_transfered', 'total_sold', 'total_adjusted', 'stock'])
                ->make(true);
        }

        $categories = Category::where('business_id', $business_id)->where('parent_id', 0)->pluck('name', 'id');
        $brands = Brands::where('business_id', $business_id)->pluck('name', 'id');
        $units = Unit::where('business_id', $business_id)->pluck('short_name', 'id');
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.stock_report', compact('categories', 'brands', 'units', 'business_locations'));
    }


    /**
     * Shows product stock details
     *
     * @return \Illuminate\Http\Response
     */
    public function getStockDetails(Request $request)
    {
        //Return the details in ajax call
        if ($request->ajax()) {
            $business_id = $request->session()->get('user.business_id');
            $product_id = $request->input('product_id');
            $query = Product::leftjoin('units as u', 'products.unit_id', '=', 'u.id')
                ->join('variations as v', 'products.id', '=', 'v.product_id')
                ->join('product_variations as pv', 'pv.id', '=', 'v.product_variation_id')
                ->leftjoin('variation_location_details as vld', 'v.id', '=', 'vld.variation_id')
                ->where('products.business_id', $business_id)
                ->where('products.id', $product_id)
                ->whereNull('v.deleted_at');

            $permitted_locations = auth()->user()->permitted_locations();
            $location_filter = '';
            if ($permitted_locations != 'all') {
                $query->whereIn('vld.location_id', $permitted_locations);
                $locations_imploded = implode(', ', $permitted_locations);
                $location_filter .= "AND transactions.location_id IN ($locations_imploded) ";
            }

            if (!empty($request->input('location_id'))) {
                $location_id = $request->input('location_id');

                $query->where('vld.location_id', $location_id);

                $location_filter .= "AND transactions.location_id=$location_id";
            }

            $product_details =  $query->select(
                'products.name as product',
                'u.short_name as unit',
                'pv.name as product_variation',
                'v.name as variation',
                'v.sub_sku as sub_sku',
                'v.sell_price_inc_tax',
                DB::raw("SUM(vld.qty_available) as stock"),
                DB::raw("(SELECT SUM(IF(transactions.type='sell', TSL.quantity - TSL.quantity_returned, -1* TPL.quantity) ) FROM transactions
                        LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id

                        LEFT JOIN purchase_lines AS TPL ON transactions.id=TPL.transaction_id

                        WHERE transactions.status='final' AND transactions.type='sell' $location_filter
                        AND (TSL.variation_id=v.id OR TPL.variation_id=v.id)) as total_sold"),
                DB::raw("(SELECT SUM(IF(transactions.type='sell_transfer', TSL.quantity, 0) ) FROM transactions
                        LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id
                        WHERE transactions.status='final' AND transactions.type='sell_transfer' $location_filter
                        AND (TSL.variation_id=v.id)) as total_transfered"),
                DB::raw("(SELECT SUM(IF(transactions.type='stock_adjustment', SAL.quantity, 0) ) FROM transactions
                        LEFT JOIN stock_adjustment_lines AS SAL ON transactions.id=SAL.transaction_id
                        WHERE transactions.status='received' AND transactions.type='stock_adjustment' $location_filter
                        AND (SAL.variation_id=v.id)) as total_adjusted")
                // DB::raw("(SELECT SUM(quantity) FROM transaction_sell_lines LEFT JOIN transactions ON transaction_sell_lines.transaction_id=transactions.id WHERE transactions.status='final' $location_filter AND
                //     transaction_sell_lines.variation_id=v.id) as total_sold")
            )
                ->groupBy('v.id')
                ->get();

            return view('report.stock_details')
                ->with(compact('product_details'));
        }
    }

    /**
     * Shows tax report of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getTaxReport(Request $request)
    {
        if (!auth()->user()->can('tax_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            $location_id = $request->get('location_id');

            $input_tax_details = $this->transactionUtil->getInputTax($business_id, $start_date, $end_date, $location_id);

            $input_tax = view('report.partials.tax_details')->with(['tax_details' => $input_tax_details])->render();

            $output_tax_details = $this->transactionUtil->getOutputTax($business_id, $start_date, $end_date, $location_id);

            $output_tax = view('report.partials.tax_details')->with(['tax_details' => $output_tax_details])->render();

            //dagdag
            //for vatable and vat
            $all_vat = $this->transactionUtil->getAllTotalVat(
                $business_id,
                $start_date,
                $end_date,
                $location_id
            );
            //for vat exempt
            $all_vat_ex = $this->transactionUtil->getAllTotalVatEx(
                $business_id,
                $start_date,
                $end_date,
                $location_id
            );

            //for zero rated
            $all_zero_rate = $this->transactionUtil->getAllTotalZeroRate(
                $business_id,
                $start_date,
                $end_date,
                $location_id
            );

            $purchase_detail1 = $this->transactionUtil->getPurchaseTotals($business_id, $start_date, $end_date, $location_id);

            $sell_detail1 = $this->transactionUtil->getSellTotals(
                $business_id,
                $start_date,
                $end_date,
                $location_id
            );

            $transaction_types = [
                'purchase_return',
                'sell_return'
            ];

            $transaction_total1 = $this->transactionUtil->getTransactionTotals(
                $business_id,
                $transaction_types,
                $start_date,
                $end_date,
                $location_id
            );

            $total_purchase_return_inc_tax1 = $transaction_total1['total_purchase_return_inc_tax'];

            $total_sell_return_inc_tax1 = $transaction_total1['total_sell_return_inc_tax'];

            $difference1 = [
                'total' => $sell_detail1['total_sell_inc_tax'],
                'vatable' => $sell_detail1['total_sell_exc_tax']
            ];

            $vatable = $all_vat['vatable'];
            $vat = $all_vat['vat'];
            $vat_ex = $all_vat_ex['vat_ex'];
            $zero_rate = $all_zero_rate['zero_rate'];



            return [
                'input_tax' => $input_tax,
                'output_tax' => $output_tax,
                'tax_diff' => $output_tax_details['total_tax'] - $input_tax_details['total_tax'],
                'sale_diff' => $difference1,
                'vatable' => $vatable,
                'vat' => $vat,
                'vat_exempt' => $vat_ex,
                'zero_rated' => $zero_rate


            ];
        }

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.tax_report')
            ->with(compact('business_locations'));
    }

    /**
     * Shows trending products
     *
     * @return \Illuminate\Http\Response
     */
    public function getTrendingProducts(Request $request)
    {
        if (!auth()->user()->can('trending_product_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        $filters = $request->only(['category', 'sub_category', 'brand', 'unit', 'limit', 'location_id']);

        $date_range = $request->input('date_range');

        if (!empty($date_range)) {
            $date_range_array = explode('~', $date_range);
            $filters['start_date'] = $this->transactionUtil->uf_date(trim($date_range_array[0]));
            $filters['end_date'] = $this->transactionUtil->uf_date(trim($date_range_array[1]));
        }

        $products = $this->productUtil->getTrendingProducts($business_id, $filters);

        $values = [];
        $labels = [];
        foreach ($products as $product) {
            $values[] = $product->total_unit_sold;
            $labels[] = $product->product . ' (' . $product->unit . ')';
        }

        $chart = (new LarapexChart)
            // ->setType('bar')
            ->setTitle('')
            ->setHeight(400)
            ->setWidth(0)  // Width set to 0 can be adjusted based on requirements
            // ->setTemplate('material')
            // ->setValues($values)
            ->setLabels($labels);
        // ->setElementLabel(__('report.total_unit_sold'));


        $categories = Category::where('business_id', $business_id)
            ->where('parent_id', 0)
            ->pluck('name', 'id');
        $brands = Brands::where('business_id', $business_id)
            ->pluck('name', 'id');
        $units = Unit::where('business_id', $business_id)
            ->pluck('short_name', 'id');
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.trending_products')
            ->with(compact('chart', 'categories', 'brands', 'units', 'business_locations'));
    }

    /**
     * Shows expense report of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getExpenseReport(Request $request)
    {
        if (!auth()->user()->can('expense_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        $filters = $request->only(['category', 'location_id']);

        $date_range = $request->input('date_range');

        if (!empty($date_range)) {
            $date_range_array = explode('~', $date_range);
            $filters['start_date'] = $this->transactionUtil->uf_date(trim($date_range_array[0]));
            $filters['end_date'] = $this->transactionUtil->uf_date(trim($date_range_array[1]));
        } else {
            $filters['start_date'] = Carbon::now()->startOfMonth()->format('Y-m-d');
            $filters['end_date'] = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $expenses = $this->transactionUtil->getExpenseReport($business_id, $filters);

        $values = [];
        $labels = [];
        foreach ($expenses as $expense) {
            $values[] = $expense->total_expense;
            $labels[] = !empty($expense->category) ? $expense->category : __('report.others');
        }

        $chart = (new LarapexChart)
            // ->setType('bar')
            ->setTitle(__('report.expense_report'))
            ->setHeight(400)
            ->setWidth(0)  // Width can be adjusted as needed
            // ->setTemplate('material')
            // ->setValues($values)
            ->setLabels($labels);
        // ->setElementLabel(__('report.total_expense'));

        $categories = ExpenseCategory::where('business_id', $business_id)
            ->pluck('name', 'id');

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.expense_report')
            ->with(compact('chart', 'categories', 'business_locations'));
    }

    /**
     * Shows stock adjustment report
     *
     * @return \Illuminate\Http\Response
     */
    public function getStockAdjustmentReport(Request $request)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');


        //Return the details in ajax call
        if ($request->ajax()) {
            $query =  Transaction::where('business_id', $business_id)
                ->where('type', 'stock_adjustment');

            //Check for permitted locations of a user
            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('location_id', $permitted_locations);
            }

            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
            }
            $location_id = $request->get('location_id');
            if (!empty($location_id)) {
                $query->where('location_id', $location_id);
            }

            $stock_adjustment_details = $query->select(
                DB::raw("SUM(final_total) as total_amount"),
                DB::raw("SUM(total_amount_recovered) as total_recovered"),
                DB::raw("SUM(IF(adjustment_type = 'normal', final_total, 0)) as total_normal"),
                DB::raw("SUM(IF(adjustment_type = 'abnormal', final_total, 0)) as total_abnormal")
            )->first();
            return response()->json($stock_adjustment_details);
        }
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.stock_adjustment_report')
            ->with(compact('business_locations'));
    }

    /**
     * Shows register report of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegisterReport(Request $request)
    {
        if (!auth()->user()->can('register_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $registers = CashRegister::join(
                'users as u',
                'u.id',
                '=',
                'cash_registers.user_id'
            )
                ->where('cash_registers.business_id', $business_id)
                ->select(
                    'cash_registers.*',
                    DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, ''), '<br>', COALESCE(email, '')) as user_name")
                );

            if (!empty($request->input('user_id'))) {
                $registers->where('cash_registers.user_id', $request->input('user_id'));
            }
            if (!empty($request->input('status'))) {
                $registers->where('cash_registers.status', $request->input('status'));
            }
            return Datatables::of($registers)
                ->editColumn('total_card_slips', function ($row) {
                    if ($row->status == 'close') {
                        return $row->total_card_slips;
                    } else {
                        return '';
                    }
                })
                ->editColumn('total_cheques', function ($row) {
                    if ($row->status == 'close') {
                        return $row->total_cheques;
                    } else {
                        return '';
                    }
                })
                ->editColumn('closed_at', function ($row) {
                    if ($row->status == 'close') {
                        return $this->productUtil->format_date($row->closed_at, true);
                    } else {
                        return '';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return $this->productUtil->format_date($row->created_at, true);
                })
                ->editColumn('closing_amount', function ($row) {
                    if ($row->status == 'close') {
                        return '<span class="display_currency" data-currency_symbol="true">' .
                            $row->closing_amount . '</span>';
                    } else {
                        return '';
                    }
                })
                ->addColumn('action', '<button type="button" data-href="{{route(\'cash-register.show\', [$id])}}" class="btn btn-xs btn-info btn-modal"
                    data-container=".view_register"><i class="fa fa-external-link" aria-hidden="true"></i> @lang("messages.view")</button>')
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, ''), '<br>', COALESCE(email, '')) like ?", ["%{$keyword}%"]);
                })
                ->rawColumns(['action', 'user_name', 'closing_amount'])
                ->make(true);
        }

        $users = User::forDropdown($business_id, false);

        return view('report.register_report')
            ->with(compact('users'));
    }

    /**
     * Shows sales representative report
     *
     * @return \Illuminate\Http\Response
     */
    public function getSalesRepresentativeReport(Request $request)
    {
        if (!auth()->user()->can('sales_representative.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        $users = User::allUsersDropdown($business_id, false);
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.sales_representative')
            ->with(compact('users', 'business_locations'));
    }

    /**
     * Shows sales representative total expense
     *
     * @return json
     */
    public function getSalesRepresentativeTotalExpense(Request $request)
    {
        if (!auth()->user()->can('sales_representative.view')) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->ajax()) {
            $business_id = $request->session()->get('user.business_id');

            $filters = $request->only(['expense_for', 'location_id', 'start_date', 'end_date']);

            $total_expense = $this->transactionUtil->getExpenseReport($business_id, $filters, 'total');

            return $total_expense;
        }
    }

    /**
     * Shows sales representative total sales
     *
     * @return json
     */
    public function getSalesRepresentativeTotalSell(Request $request)
    {
        if (!auth()->user()->can('sales_representative.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            $location_id = $request->get('location_id');
            $created_by = $request->get('created_by');

            $sell_details = $this->transactionUtil->getSellTotals($business_id, $start_date, $end_date, $location_id, $created_by);

            //Get Sell Return details
            $transaction_types = [
                'sell_return'
            ];
            $sell_return_details = $this->transactionUtil->getTransactionTotals(
                $business_id,
                $transaction_types,
                $start_date,
                $end_date,
                $location_id,
                $created_by
            );

            $total_sell_return = !empty($sell_return_details['total_sell_return_exc_tax']) ? $sell_return_details['total_sell_return_exc_tax'] : 0;
            $total_sell = $sell_details['total_sell_exc_tax'] - $total_sell_return;

            return [
                'total_sell_exc_tax' => $sell_details['total_sell_exc_tax'],
                'total_sell_return_exc_tax' => $total_sell_return,
                'total_sell' => $total_sell
            ];
        }
    }

    /**
     * Shows sales representative total commission
     *
     * @return json
     */
    public function getSalesRepresentativeTotalCommission(Request $request)
    {
        if (!auth()->user()->can('sales_representative.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            $location_id = $request->get('location_id');
            $commission_agent = $request->get('commission_agent');

            $sell_details = $this->transactionUtil->getTotalSellCommission($business_id, $start_date, $end_date, $location_id, $commission_agent);

            //Get Commision
            $commission_percentage = User::find($commission_agent)->cmmsn_percent;
            $total_commission = $commission_percentage * $sell_details['total_sales_with_commission'] / 100;

            return [
                'total_sales_with_commission' =>
                $sell_details['total_sales_with_commission'],
                'total_commission' => $total_commission,
                'commission_percentage' => $commission_percentage
            ];
        }
    }

    /**
     * Shows product stock expiry report
     *
     * @return \Illuminate\Http\Response
     */
    public function getStockExpiryReport(Request $request)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $query = PurchaseLine::leftjoin(
                'transactions as t',
                'purchase_lines.transaction_id',
                '=',
                't.id'
            )
                ->leftjoin(
                    'products as p',
                    'purchase_lines.product_id',
                    '=',
                    'p.id'
                )
                ->leftjoin(
                    'variations as v',
                    'purchase_lines.variation_id',
                    '=',
                    'v.id'
                )
                ->leftjoin(
                    'product_variations as pv',
                    'v.product_variation_id',
                    '=',
                    'pv.id'
                )
                ->leftjoin('business_locations as l', 't.location_id', '=', 'l.id')
                ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
                ->where('t.business_id', $business_id)
                //->whereNotNull('p.expiry_period')
                //->whereNotNull('p.expiry_period_type')
                //->whereNotNull('exp_date')
                ->where('p.enable_stock', 1)
                ->whereRaw('purchase_lines.quantity > purchase_lines.quantity_sold + quantity_adjusted + quantity_returned');

            $permitted_locations = auth()->user()->permitted_locations();

            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            if (!empty($request->input('location_id'))) {
                $location_id = $request->input('location_id');
                $query->where('t.location_id', $location_id);
            }

            if (!empty($request->input('category_id'))) {
                $query->where('p.category_id', $request->input('category_id'));
            }
            if (!empty($request->input('sub_category_id'))) {
                $query->where('p.sub_category_id', $request->input('sub_category_id'));
            }
            if (!empty($request->input('brand_id'))) {
                $query->where('p.brand_id', $request->input('brand_id'));
            }
            if (!empty($request->input('unit_id'))) {
                $query->where('p.unit_id', $request->input('unit_id'));
            }
            if (!empty($request->input('exp_date_filter'))) {
                $query->whereDate('exp_date', '<=', $request->input('exp_date_filter'));
            }

            $report = $query->select(
                'p.name as product',
                'p.sku',
                'p.type as product_type',
                'v.name as variation',
                'pv.name as product_variation',
                'l.name as location',
                'mfg_date',
                'exp_date',
                'u.short_name as unit',
                DB::raw("SUM(COALESCE(quantity, 0) - COALESCE(quantity_sold, 0) - COALESCE(quantity_adjusted, 0) - COALESCE(quantity_returned, 0)) as stock_left"),
                't.ref_no',
                't.id as transaction_id',
                'purchase_lines.id as purchase_line_id',
                'purchase_lines.lot_number'
            )
                ->groupBy('purchase_lines.id');

            return Datatables::of($report)
                ->editColumn('name', function ($row) {
                    if ($row->product_type == 'variable') {
                        return $row->product . ' - ' .
                            $row->product_variation . ' - ' . $row->variation;
                    } else {
                        return $row->product;
                    }
                })
                ->editColumn('mfg_date', function ($row) {
                    if (!empty($row->mfg_date)) {
                        return $this->productUtil->format_date($row->mfg_date);
                    } else {
                        return '--';
                    }
                })
                ->editColumn('exp_date', function ($row) {
                    if (!empty($row->exp_date)) {
                        $carbon_exp = Carbon::createFromFormat('Y-m-d', $row->exp_date);
                        $carbon_now = Carbon::now();
                        if ($carbon_now->diffInDays($carbon_exp, false) >= 0) {
                            return $this->productUtil->format_date($row->exp_date) . '<br><small>( <span class="time-to-now">' . $row->exp_date . '</span> )</small>';
                        } else {
                            return $this->productUtil->format_date($row->exp_date) . ' &nbsp; <span class="label label-danger no-print">' . __('report.expired') . '</span><span class="print_section">' . __('report.expired') . '</span><br><small>( <span class="time-from-now">' . $row->exp_date . '</span> )</small>';
                        }
                    } else {
                        return '--';
                    }
                })
                ->editColumn('ref_no', function ($row) {
                    return '<button type="button" data-href="' . route('purchases.show', [$row->transaction_id])
                        . '" class="btn btn-link btn-modal" data-container=".view_modal"  >' . $row->ref_no . '</button>';
                })
                ->editColumn('stock_left', function ($row) {
                    return '<span data-is_quantity="true" class="display_currency stock_left" data-currency_symbol=false data-orig-value="' . $row->stock_left . '" data-unit="' . $row->unit . '" >' . $row->stock_left . '</span> ' . $row->unit;
                })
                ->addColumn('edit', function ($row) {
                    $html =  '<button type="button" class="btn btn-primary btn-xs stock_expiry_edit_btn" data-transaction_id="' . $row->transaction_id . '" data-purchase_line_id="' . $row->purchase_line_id . '"> <i class="fa fa-edit"></i> ' . __("messages.edit") .
                        '</button>';

                    if (!empty($row->exp_date)) {
                        $carbon_exp = Carbon::createFromFormat('Y-m-d', $row->exp_date);
                        $carbon_now = Carbon::now();
                        if ($carbon_now->diffInDays($carbon_exp, false) < 0) {
                            $html .=  ' <button type="button" class="btn btn-warning btn-xs remove_from_stock_btn" data-href="' . route('stock-adjustments.removeExpiredStock', [$row->purchase_line_id]) . '"> <i class="fa fa-trash"></i> ' . __("lang_v1.remove_from_stock") .
                                '</button>';
                        }
                    }

                    return $html;
                })
                ->rawColumns(['exp_date', 'ref_no', 'edit', 'stock_left'])
                ->make(true);
        }

        $categories = Category::where('business_id', $business_id)
            ->where('parent_id', 0)
            ->pluck('name', 'id');
        $brands = Brands::where('business_id', $business_id)
            ->pluck('name', 'id');
        $units = Unit::where('business_id', $business_id)
            ->pluck('short_name', 'id');
        $business_locations = BusinessLocation::forDropdown($business_id, true);
        $view_stock_filter = [
            Carbon::now()->subDay()->format('Y-m-d') => __('report.expired'),
            Carbon::now()->addWeek()->format('Y-m-d') => __('report.expiring_in_1_week'),
            Carbon::now()->addDays(15)->format('Y-m-d') => __('report.expiring_in_15_days'),
            Carbon::now()->addMonth()->format('Y-m-d') => __('report.expiring_in_1_month'),
            Carbon::now()->addMonths(3)->format('Y-m-d') => __('report.expiring_in_3_months'),
            Carbon::now()->addMonths(6)->format('Y-m-d') => __('report.expiring_in_6_months'),
            Carbon::now()->addYear()->format('Y-m-d') => __('report.expiring_in_1_year')
        ];

        return view('report.stock_expiry_report')
            ->with(compact('categories', 'brands', 'units', 'business_locations', 'view_stock_filter'));
    }

    /**
     * Shows product stock expiry report
     *
     * @return \Illuminate\Http\Response
     */
    public function getStockExpiryReportEditModal(Request $request, $purchase_line_id)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $purchase_line = PurchaseLine::join(
                'transactions as t',
                'purchase_lines.transaction_id',
                '=',
                't.id'
            )
                ->join(
                    'products as p',
                    'purchase_lines.product_id',
                    '=',
                    'p.id'
                )
                ->where('purchase_lines.id', $purchase_line_id)
                ->where('t.business_id', $business_id)
                ->select(['purchase_lines.*', 'p.name', 't.ref_no'])
                ->first();

            if (!empty($purchase_line)) {
                if (!empty($purchase_line->exp_date)) {
                    $purchase_line->exp_date = date('m/d/Y', strtotime($purchase_line->exp_date));
                }
            }

            return view('report.partials.stock_expiry_edit_modal')
                ->with(compact('purchase_line'));
        }
    }

    /**
     * Update product stock expiry report
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStockExpiryReport(Request $request)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('user.business_id');

            //Return the details in ajax call
            if ($request->ajax()) {
                DB::beginTransaction();

                $input = $request->only(['purchase_line_id', 'exp_date']);

                $purchase_line = PurchaseLine::join(
                    'transactions as t',
                    'purchase_lines.transaction_id',
                    '=',
                    't.id'
                )
                    ->join(
                        'products as p',
                        'purchase_lines.product_id',
                        '=',
                        'p.id'
                    )
                    ->where('purchase_lines.id', $input['purchase_line_id'])
                    ->where('t.business_id', $business_id)
                    ->select(['purchase_lines.*', 'p.name', 't.ref_no'])
                    ->first();

                if (!empty($purchase_line) && !empty($input['exp_date'])) {
                    $purchase_line->exp_date = $this->productUtil->uf_date($input['exp_date']);
                    $purchase_line->save();
                }

                DB::commit();

                $output = [
                    'success' => 1,
                    'msg' => __('lang_v1.updated_succesfully')
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return $output;
    }

    /**
     * Shows product stock expiry report
     *
     * @return \Illuminate\Http\Response
     */
    public function getCustomerGroup(Request $request)
    {
        if (!auth()->user()->can('contacts_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        if ($request->ajax()) {
            $query = Transaction::leftjoin('customer_groups AS CG', 'transactions.customer_group_id', '=', 'CG.id')
                ->where('transactions.business_id', $business_id)
                ->where('transactions.type', 'sell')
                ->where('transactions.status', 'final')
                ->groupBy('transactions.customer_group_id')
                ->select(DB::raw("SUM(final_total) as total_sell"), 'CG.name');

            $group_id = $request->get('customer_group_id', null);
            if (!empty($group_id)) {
                $query->where('transactions.customer_group_id', $group_id);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('transactions.location_id', $permitted_locations);
            }

            $location_id = $request->get('location_id', null);
            if (!empty($location_id)) {
                $query->where('transactions.location_id', $location_id);
            }

            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
            }


            return Datatables::of($query)
                ->editColumn('total_sell', function ($row) {
                    return '<span class="display_currency" data-currency_symbol = true>' . $row->total_sell . '</span>';
                })
                ->rawColumns(['total_sell'])
                ->make(true);
        }

        $customer_group = CustomerGroup::forDropdown($business_id, false, true);
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.customer_group')
            ->with(compact('customer_group', 'business_locations'));
    }

    /**
     * Shows product purchase report
     *
     * @return \Illuminate\Http\Response
     */
    public function getproductPurchaseReport(Request $request)
    {
        if (!auth()->user()->can('tax_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        // if (!auth()->user()->can('purchase_n_sell_report.view')) {
        //     abort(403, 'Unauthorized action.');
        // }

        $business_id = $request->session()->get('user.business_id');
        if ($request->ajax()) {
            $variation_id = $request->get('variation_id', null);
            $query = PurchaseLine::join(
                'transactions as t',
                'purchase_lines.transaction_id',
                '=',
                't.id'
            )
                ->join(
                    'variations as v',
                    'purchase_lines.variation_id',
                    '=',
                    'v.id'
                )
                ->join('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
                ->join('contacts as c', 't.contact_id', '=', 'c.id')
                ->join('products as p', 'pv.product_id', '=', 'p.id')
                ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
                ->where('t.business_id', $business_id)
                ->where('t.type', 'purchase')
                ->select(
                    'p.name as product_name',
                    'p.type as product_type',
                    'pv.name as product_variation',
                    'v.name as variation_name',
                    'c.name as supplier',
                    't.id as transaction_id',
                    't.ref_no',
                    't.transaction_date as transaction_date',
                    'purchase_lines.purchase_price_inc_tax as unit_purchase_price',
                    DB::raw('(purchase_lines.quantity - purchase_lines.quantity_returned) as purchase_qty'),
                    'purchase_lines.quantity_adjusted',
                    'u.short_name as unit',
                    DB::raw('((purchase_lines.quantity - purchase_lines.quantity_returned - purchase_lines.quantity_adjusted) * purchase_lines.purchase_price_inc_tax) as subtotal')
                )
                ->groupBy('purchase_lines.id');
            if (!empty($variation_id)) {
                $query->where('purchase_lines.variation_id', $variation_id);
            }
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            $location_id = $request->get('location_id', null);
            if (!empty($location_id)) {
                $query->where('t.location_id', $location_id);
            }

            $supplier_id = $request->get('supplier_id', null);
            if (!empty($supplier_id)) {
                $query->where('t.contact_id', $supplier_id);
            }

            return Datatables::of($query)
                ->editColumn('product_name', function ($row) {
                    $product_name = $row->product_name;
                    if ($row->product_type == 'variable') {
                        $product_name .= ' - ' . $row->product_variation . ' - ' . $row->variation_name;
                    }

                    return $product_name;
                })
                ->editColumn('ref_no', function ($row) {
                    return '<a data-href="' . route('purchases.show', [$row->transaction_id])
                        . '" href="#" data-container=".view_modal" class="btn-modal">' . $row->ref_no . '</a>';
                })
                ->editColumn('purchase_qty', function ($row) {
                    return '<span data-is_quantity="true" class="display_currency purchase_qty" data-currency_symbol=false data-orig-value="' . (float)$row->purchase_qty . '" data-unit="' . $row->unit . '" >' . (float) $row->purchase_qty . '</span> ' . $row->unit;
                })
                ->editColumn('quantity_adjusted', function ($row) {
                    return '<span data-is_quantity="true" class="display_currency quantity_adjusted" data-currency_symbol=false data-orig-value="' . (float)$row->quantity_adjusted . '" data-unit="' . $row->unit . '" >' . (float) $row->quantity_adjusted . '</span> ' . $row->unit;
                })
                ->editColumn('subtotal', function ($row) {
                    return '<span class="display_currency row_subtotal" data-currency_symbol=true data-orig-value="' . $row->subtotal . '">' . $row->subtotal . '</span>';
                })
                ->editColumn('transaction_date', '@format_date($transaction_date)')
                ->editColumn('unit_purchase_price', function ($row) {
                    return '<span class="display_currency" data-currency_symbol = true>' . $row->unit_purchase_price . '</span>';
                })
                ->rawColumns(['ref_no', 'unit_purchase_price', 'subtotal', 'purchase_qty', 'quantity_adjusted'])
                ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id);
        $suppliers = Contact::suppliersDropdown($business_id);

        return view('report.product_purchase_report')
            ->with(compact('business_locations', 'suppliers'));
    }


    public function getproductOpeningStocksReport(Request $request)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        if ($request->ajax()) {
            $query = PurchaseLine::join(
                'transactions as t',
                'purchase_lines.transaction_id',
                '=',
                't.id'
            )
                ->join('products as p', 'purchase_lines.product_id', '=', 'p.id')
                ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
                ->where('t.business_id', $business_id)
                ->where('t.type', 'opening_stock')
                ->select(
                    'p.name as product_name',
                    'p.type as product_type',
                    't.id as transaction_id',
                    'u.short_name as unit',
                    'purchase_lines.quantity as opening_qty'
                )
                ->groupBy('purchase_lines.id');

            return Datatables::of($query)
                ->editColumn('product_name', function ($row) {
                    $product_name = $row->product_name;
                    if ($row->product_type == 'variable') {
                        $product_name .= ' - ' . $row->product_variation . ' - ' . $row->variation_name;
                    }

                    return $product_name;
                })
                ->editColumn('opening_qty', function ($row) {
                    return '<span data-is_quantity="true" class="display_currency opening_qty" data-currency_symbol=false data-orig-value="' . (float)$row->opening_qty . '" data-unit="' . $row->unit . '" >' . (float) $row->opening_qty . '</span> ' . $row->unit;
                })
                ->rawColumns(['opening_qty'])
                ->make(true);
        }

        //$business_locations = BusinessLocation::forDropdown($business_id);
        //$suppliers = Contact::suppliersDropdown($business_id);

        return view('report.opening_stock_report');
    }

    /**
     * Shows product purchase report
     *
     * @return \Illuminate\Http\Response
     */
    public function getproductSellReport(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        if ($request->ajax()) {
            $variation_id = $request->get('variation_id', null);
            $query = TransactionSellLine::join(
                'transactions as t',
                'transaction_sell_lines.transaction_id',
                '=',
                't.id'
            )
                ->join(
                    'variations as v',
                    'transaction_sell_lines.variation_id',
                    '=',
                    'v.id'
                )
                ->join('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
                ->join('contacts as c', 't.contact_id', '=', 'c.id')
                ->join('products as p', 'pv.product_id', '=', 'p.id')
                ->leftjoin('tax_rates', 'transaction_sell_lines.tax_id', '=', 'tax_rates.id')
                ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
                ->where('t.business_id', $business_id)
                ->where('t.type', 'sell')
                ->where('t.status', 'final')
                ->select(
                    'p.name as product_name',
                    'p.type as product_type',
                    'pv.name as product_variation',
                    'v.name as variation_name',
                    'c.name as customer',
                    't.id as transaction_id',
                    't.invoice_no',
                    't.transaction_date as transaction_date',
                    'transaction_sell_lines.unit_price_before_discount as unit_price',
                    'transaction_sell_lines.unit_price_inc_tax as unit_sale_price',
                    DB::raw('(transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) as sell_qty'),
                    'transaction_sell_lines.line_discount_type as discount_type',
                    'transaction_sell_lines.line_discount_amount as discount_amount',
                    'transaction_sell_lines.item_tax',
                    'tax_rates.name as tax',
                    'u.short_name as unit',
                    DB::raw('((transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) * transaction_sell_lines.unit_price_inc_tax) as subtotal')
                )
                ->groupBy('transaction_sell_lines.id');

            if (!empty($variation_id)) {
                $query->where('transaction_sell_lines.variation_id', $variation_id);
            }
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            $location_id = $request->get('location_id', null);
            if (!empty($location_id)) {
                $query->where('t.location_id', $location_id);
            }

            $customer_id = $request->get('customer_id', null);
            if (!empty($customer_id)) {
                $query->where('t.contact_id', $customer_id);
            }

            return Datatables::of($query)
                ->editColumn('product_name', function ($row) {
                    $product_name = $row->product_name;
                    if ($row->product_type == 'variable') {
                        $product_name .= ' - ' . $row->product_variation . ' - ' . $row->variation_name;
                    }

                    return $product_name;
                })
                ->editColumn('invoice_no', function ($row) {
                    return '<a data-href="' . action('SellController@show', [$row->transaction_id])
                        . '" href="#" data-container=".view_modal" class="btn-modal">' . $row->invoice_no . '</a>';
                })
                ->editColumn('transaction_date', '@format_date($transaction_date)')
                ->editColumn('unit_sale_price', function ($row) {
                    return '<span class="display_currency" data-currency_symbol = true>' . $row->unit_sale_price . '</span>';
                })
                ->editColumn('sell_qty', function ($row) {
                    return '<span data-is_quantity="true" class="display_currency sell_qty" data-currency_symbol=false data-orig-value="' . (float)$row->sell_qty . '" data-unit="' . $row->unit . '" >' . (float) $row->sell_qty . '</span> ' . $row->unit;
                })
                ->editColumn('subtotal', function ($row) {
                    return '<span class="display_currency row_subtotal" data-currency_symbol = true data-orig-value="' . $row->subtotal . '">' . $row->subtotal . '</span>';
                })
                ->editColumn('unit_price', function ($row) {
                    return '<span class="display_currency" data-currency_symbol = true>' . $row->unit_price . '</span>';
                })
                ->editColumn('discount_amount', '
                    @if($discount_type == "percentage")
                        {{@number_format($discount_amount)}} %
                    @elseif($discount_type == "fixed")
                        {{@number_format($discount_amount)}}
                    @endif
                    ')
                ->editColumn('tax', function ($row) {
                    return '<span class="display_currency" data-currency_symbol = true>' .
                        $row->item_tax .
                        '</span>' . '<br>' . '<span class="tax" data-orig-value="' . (float)$row->item_tax . '" data-unit="' . $row->tax . '"><small>(' . $row->tax . ')</small></span>';
                })
                ->rawColumns(['invoice_no', 'unit_sale_price', 'subtotal', 'sell_qty', 'discount_amount', 'unit_price', 'tax'])
                ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id);
        $customers = Contact::customersDropdown($business_id);

        return view('report.product_sell_report')
            ->with(compact('business_locations', 'customers'));
    }

    /**
     * Shows product lot report
     *
     * @return \Illuminate\Http\Response
     */
    public function getLotReport(Request $request)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $query = Product::where('products.business_id', $business_id)
                ->leftjoin('units', 'products.unit_id', '=', 'units.id')
                ->join('variations as v', 'products.id', '=', 'v.product_id')
                ->join('purchase_lines as pl', 'v.id', '=', 'pl.variation_id')
                ->leftjoin(
                    'transaction_sell_lines_purchase_lines as tspl',
                    'pl.id',
                    '=',
                    'tspl.purchase_line_id'
                )
                ->join('transactions as t', 'pl.transaction_id', '=', 't.id');

            $permitted_locations = auth()->user()->permitted_locations();
            $location_filter = 'WHERE ';

            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);

                $locations_imploded = implode(', ', $permitted_locations);
                $location_filter = " LEFT JOIN transactions as t2 on pls.transaction_id=t2.id WHERE t2.location_id IN ($locations_imploded) AND ";
            }

            if (!empty($request->input('location_id'))) {
                $location_id = $request->input('location_id');
                $query->where('t.location_id', $location_id);

                $location_filter = "LEFT JOIN transactions as t2 on pls.transaction_id=t2.id WHERE t2.location_id=$location_id AND ";
            }

            if (!empty($request->input('category_id'))) {
                $query->where('products.category_id', $request->input('category_id'));
            }

            if (!empty($request->input('sub_category_id'))) {
                $query->where('products.sub_category_id', $request->input('sub_category_id'));
            }

            if (!empty($request->input('brand_id'))) {
                $query->where('products.brand_id', $request->input('brand_id'));
            }

            if (!empty($request->input('unit_id'))) {
                $query->where('products.unit_id', $request->input('unit_id'));
            }

            $products = $query->select(
                'products.name as product',
                'v.name as variation_name',
                'sub_sku',
                'pl.lot_number',
                'pl.exp_date as exp_date',
                DB::raw("( COALESCE((SELECT SUM(quantity - quantity_returned) from purchase_lines as pls $location_filter variation_id = v.id AND lot_number = pl.lot_number), 0) -
                    SUM(COALESCE((tspl.quantity - tspl.qty_returned), 0))) as stock"),
                // DB::raw("(SELECT SUM(IF(transactions.type='sell', TSL.quantity, -1* TPL.quantity) ) FROM transactions
                //         LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id

                //         LEFT JOIN purchase_lines AS TPL ON transactions.id=TPL.transaction_id

                //         WHERE transactions.status='final' AND transactions.type IN ('sell', 'sell_return') $location_filter
                //         AND (TSL.product_id=products.id OR TPL.product_id=products.id)) as total_sold"),

                DB::raw("COALESCE(SUM(IF(tspl.sell_line_id IS NULL, 0, (tspl.quantity - tspl.qty_returned)) ), 0) as total_sold"),
                DB::raw("COALESCE(SUM(IF(tspl.stock_adjustment_line_id IS NULL, 0, tspl.quantity ) ), 0) as total_adjusted"),
                'products.type',
                'units.short_name as unit'
            )
                ->whereNotNull('pl.lot_number')
                ->groupBy('v.id')
                ->groupBy('pl.lot_number');

            return Datatables::of($products)
                ->editColumn('stock', function ($row) {
                    $stock = $row->stock ? $row->stock : 0;
                    return '<span data-is_quantity="true" class="display_currency total_stock" data-currency_symbol=false data-orig-value="' . (float)$stock . '" data-unit="' . $row->unit . '" >' . (float)$stock . '</span> ' . $row->unit;
                })
                ->editColumn('product', function ($row) {
                    if ($row->variation_name != 'DUMMY') {
                        return $row->product . ' (' . $row->variation_name . ')';
                    } else {
                        return $row->product;
                    }
                })
                ->editColumn('total_sold', function ($row) {
                    if ($row->total_sold) {
                        return '<span data-is_quantity="true" class="display_currency total_sold" data-currency_symbol=false data-orig-value="' . (float)$row->total_sold . '" data-unit="' . $row->unit . '" >' . (float)$row->total_sold . '</span> ' . $row->unit;
                    } else {
                        return '0' . ' ' . $row->unit;
                    }
                })
                ->editColumn('total_adjusted', function ($row) {
                    if ($row->total_adjusted) {
                        return '<span data-is_quantity="true" class="display_currency total_adjusted" data-currency_symbol=false data-orig-value="' . (float)$row->total_adjusted . '" data-unit="' . $row->unit . '" >' . (float)$row->total_adjusted . '</span> ' . $row->unit;
                    } else {
                        return '0' . ' ' . $row->unit;
                    }
                })
                ->editColumn('exp_date', function ($row) {
                    if (!empty($row->exp_date)) {
                        $carbon_exp = Carbon::createFromFormat('Y-m-d', $row->exp_date);
                        $carbon_now = Carbon::now();
                        if ($carbon_now->diffInDays($carbon_exp, false) >= 0) {
                            return $this->productUtil->format_date($row->exp_date) . '<br><small>( <span class="time-to-now">' . $row->exp_date . '</span> )</small>';
                        } else {
                            return $this->productUtil->format_date($row->exp_date) . ' &nbsp; <span class="label label-danger no-print">' . __('report.expired') . '</span><span class="print_section">' . __('report.expired') . '</span><br><small>( <span class="time-from-now">' . $row->exp_date . '</span> )</small>';
                        }
                    } else {
                        return '--';
                    }
                })
                ->removeColumn('unit')
                ->removeColumn('id')
                ->removeColumn('variation_name')
                ->rawColumns(['exp_date', 'stock', 'total_sold', 'total_adjusted'])
                ->make(true);
        }

        $categories = Category::where('business_id', $business_id)
            ->where('parent_id', 0)
            ->pluck('name', 'id');
        $brands = Brands::where('business_id', $business_id)
            ->pluck('name', 'id');
        $units = Unit::where('business_id', $business_id)
            ->pluck('short_name', 'id');
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.lot_report')
            ->with(compact('categories', 'brands', 'units', 'business_locations'));
    }

    /**
     * Shows purchase payment report
     *
     * @return \Illuminate\Http\Response
     */
    // old query
    public function purchasePaymentReport(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        $supplier_id = $request->get('supplier_id', null);
        if ($request->ajax()) {
            $supplier_id = $request->get('supplier_id', null);
            $contact_filter1 = !empty($supplier_id) ? "AND t.contact_id=$supplier_id" : '';
            $contact_filter2 = !empty($supplier_id) ? "AND transactions.contact_id=$supplier_id" : '';

            $query = TransactionPayment::leftjoin('transactions as t', function ($join) use ($business_id) {
                $join->on('transaction_payments.transaction_id', '=', 't.id')
                    ->where('t.business_id', $business_id)
                    ->whereIn('t.type', ['purchase', 'opening_balance']);
            })
                ->where('transaction_payments.business_id', $business_id)
                ->where(function ($q) use ($business_id, $contact_filter1, $contact_filter2) {
                    $q->whereRaw("(transaction_payments.transaction_id IS NOT NULL AND t.type IN ('purchase', 'opening_balance')  AND transaction_payments.parent_id IS NULL $contact_filter1)")
                        ->orWhereRaw("EXISTS(SELECT * FROM transaction_payments as tp JOIN transactions ON tp.transaction_id = transactions.id WHERE transactions.type IN ('purchase', 'opening_balance') AND transactions.business_id = $business_id AND tp.parent_id=transaction_payments.id $contact_filter2)");
                })

                ->select(
                    DB::raw("IF(transaction_payments.transaction_id IS NULL,
                                (SELECT c.name FROM transactions as ts
                                JOIN contacts as c ON ts.contact_id=c.id
                                WHERE ts.id=(
                                        SELECT tps.transaction_id FROM transaction_payments as tps
                                        WHERE tps.parent_id=transaction_payments.id LIMIT 1
                                    )
                                ),
                                (SELECT c.name FROM transactions as ts JOIN
                                    contacts as c ON ts.contact_id=c.id
                                    WHERE ts.id=t.id
                                )
                            ) as supplier"),
                    'transaction_payments.amount',
                    'method',
                    'paid_on',
                    'transaction_payments.payment_ref_no',
                    'transaction_payments.document',
                    't.ref_no',
                    't.id as transaction_id',
                    'cheque_number',
                    'card_transaction_number',
                    'bank_account_number',
                    'transaction_no',
                    'transaction_payments.id as DT_RowId'
                )
                ->groupBy('transaction_payments.id');

            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(paid_on)'), [$start_date, $end_date]);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            $location_id = $request->get('location_id', null);
            if (!empty($location_id)) {
                $query->where('t.location_id', $location_id);
            }

            return Datatables::of($query)
                ->editColumn('ref_no', function ($row) {
                    if (!empty($row->ref_no)) {
                        return '<a data-href="' . route('purchases.show', [$row->transaction_id])
                            . '" href="#" data-container=".view_modal" class="btn-modal">' . $row->ref_no . '</a>';
                    } else {
                        return '';
                    }
                })

                ->editColumn('paid_on', '@format_date($paid_on)')
                ->editColumn('method', function ($row) {
                    $method = __('lang_v1.' . $row->method);
                    if ($row->method == 'cheque') {
                        $method .= '<br>(' . __('lang_v1.cheque_no') . ': ' . $row->cheque_number . ')';
                    } elseif ($row->method == 'card') {
                        $method .= '<br>(' . __('lang_v1.card_transaction_no') . ': ' . $row->card_transaction_number . ')';
                    } elseif ($row->method == 'bank_transfer') {
                        $method .= '<br>(' . __('lang_v1.bank_account_no') . ': ' . $row->bank_account_number . ')';
                    } elseif ($row->method == 'custom_pay_1') {
                        $method = __('lang_v1.custom_payment_1') . '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
                    } elseif ($row->method == 'custom_pay_2') {
                        $method = __('lang_v1.custom_payment_2') . '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
                    } elseif ($row->method == 'custom_pay_3') {
                        $method = __('lang_v1.custom_payment_3') . '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
                    }
                    return $method;
                })
                ->editColumn('amount', function ($row) {
                    return '<span class="display_currency paid-amount" data-currency_symbol = true data-orig-value="' . $row->amount . '">' . $row->amount . '</span>';
                })
                ->addColumn('action', '<button type="button" class="btn btn-primary btn-xs view_payment" data-href="{{ route("payments.viewPayment", [$DT_RowId]) }}">@lang("messages.view")
                    </button> @if(!empty($document))<a href="{{asset("/uploads/documents/" . $document)}}" class="btn btn-success btn-xs" download=""><i class="fa fa-download"></i> @lang("purchase.download_document")</a>@endif')
                ->rawColumns(['ref_no', 'amount', 'method', 'action'])
                ->make(true);
        }
        $business_locations = BusinessLocation::forDropdown($business_id);
        $suppliers = Contact::suppliersDropdown($business_id, false);

        return view('report.purchase_payment_report')
            ->with(compact('business_locations', 'suppliers'));
    }


    // optimize query
    // public function purchasePaymentReport(Request $request)
    // {
    //     if (!auth()->user()->can('purchase_n_sell_report.view')) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     $business_id = $request->session()->get('user.business_id');

    //     if ($request->ajax()) {
    //         $supplier_id = $request->get('supplier_id');

    //         $query = TransactionPayment::from('transaction_payments')
    //             ->leftJoin('transactions as t', function ($join) use ($business_id) {
    //                 $join->on('transaction_payments.transaction_id', '=', 't.id')
    //                     ->where('t.business_id', $business_id)
    //                     ->whereIn('t.type', ['purchase', 'opening_balance']);
    //             })
    //             ->leftJoin('contacts as main_contact', 't.contact_id', '=', 'main_contact.id')
    //             ->leftJoin('transaction_payments as tps', 'tps.parent_id', '=', 'transaction_payments.id')
    //             ->leftJoin('transactions as parent_ts', function ($join) use ($business_id) {
    //                 $join->on('tps.transaction_id', '=', 'parent_ts.id')
    //                     ->where('parent_ts.business_id', $business_id)
    //                     ->whereIn('parent_ts.type', ['purchase', 'opening_balance']);
    //             })
    //             ->leftJoin('contacts as parent_contact', 'parent_ts.contact_id', '=', 'parent_contact.id')
    //             ->where('transaction_payments.business_id', $business_id)
    //             ->where(function ($q) use ($supplier_id) {
    //                 $q->where(function ($q2) use ($supplier_id) {
    //                     $q2->whereNotNull('transaction_payments.transaction_id')
    //                         ->whereNull('transaction_payments.parent_id');
    //                     if ($supplier_id) {
    //                         $q2->where('t.contact_id', $supplier_id);
    //                     }
    //                 })
    //                     ->orWhere(function ($q3) use ($supplier_id) {
    //                         $q3->whereExists(function ($sub) use ($supplier_id) {
    //                             $sub->select(DB::raw(1))
    //                                 ->from('transaction_payments as tp2')
    //                                 ->join('transactions as tr', 'tp2.transaction_id', '=', 'tr.id')
    //                                 ->whereColumn('tp2.parent_id', 'transaction_payments.id')
    //                                 ->whereIn('tr.type', ['purchase', 'opening_balance']);
    //                             if ($supplier_id) {
    //                                 $sub->where('tr.contact_id', $supplier_id);
    //                             }
    //                         });
    //                     });
    //             })
    //             ->select([
    //                 DB::raw('COALESCE(main_contact.name, parent_contact.name) as supplier'),
    //                 'transaction_payments.amount',
    //                 'transaction_payments.method',
    //                 'transaction_payments.paid_on',
    //                 'transaction_payments.payment_ref_no',
    //                 'transaction_payments.document',
    //                 DB::raw('COALESCE(t.ref_no, parent_ts.ref_no) as ref_no'),
    //                 DB::raw('COALESCE(t.id, parent_ts.id) as transaction_id'),
    //                 'transaction_payments.cheque_number',
    //                 'transaction_payments.card_transaction_number',
    //                 'transaction_payments.bank_account_number',
    //                 'transaction_payments.transaction_no',
    //                 'transaction_payments.id as DT_RowId',
    //             ])
    //             ->groupBy('transaction_payments.id');

    //         if ($start = $request->get('start_date')) {
    //             if ($end = $request->get('end_date')) {
    //                 $query->whereBetween(DB::raw('date(transaction_payments.paid_on)'), [$start, $end]);
    //             }
    //         }


    //         $permitted = auth()->user()->permitted_locations();
    //         if ($permitted !== 'all') {
    //             $query->whereIn('t.location_id', $permitted);
    //         }

    //         if ($loc = $request->get('location_id')) {
    //             $query->where('t.location_id', $loc);
    //         }

    //         return Datatables::of($query)
    //             ->editColumn('ref_no', function ($row) {
    //                 return $row->ref_no
    //                     ? '<a data-href="' . route('purchases.show', $row->transaction_id) . '" href="#" data-container=".view_modal" class="btn-modal">'
    //                     . $row->ref_no . '</a>'
    //                     : '';
    //             })
    //             ->editColumn('paid_on', '@format_date($paid_on)')
    //             ->editColumn('method', function ($row) {
    //                 $m = __('lang_v1.' . $row->method);
    //                 switch ($row->method) {
    //                     case 'cheque':
    //                         $m .= '<br>(' . __('lang_v1.cheque_no') . ': ' . $row->cheque_number . ')';
    //                         break;
    //                     case 'card':
    //                         $m .= '<br>(' . __('lang_v1.card_transaction_no') . ': ' . $row->card_transaction_number . ')';
    //                         break;
    //                     case 'bank_transfer':
    //                         $m .= '<br>(' . __('lang_v1.bank_account_no') . ': ' . $row->bank_account_number . ')';
    //                         break;
    //                     case 'custom_pay_1':
    //                     case 'custom_pay_2':
    //                     case 'custom_pay_3':
    //                         $m = __('lang_v1.' . $row->method) . '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
    //                         break;
    //                 }
    //                 return $m;
    //             })
    //             ->editColumn('amount', function ($row) {
    //                 return '<span class="display_currency paid-amount" data-currency_symbol=true data-orig-value="' . $row->amount . '">'
    //                     . $row->amount . '</span>';
    //             })
    //             ->addColumn('action', function ($row) {
    //                 $btn = '<button type="button" class="btn btn-primary btn-xs view_payment" data-href="'
    //                     . route('payments.viewPayment', [$row->DT_RowId]) . '">'
    //                     . __('messages.view') . '</button>';
    //                 if (!empty($row->document)) {
    //                     $btn .= ' <a href="' . asset('/uploads/documents/' . $row->document) . '" class="btn btn-success btn-xs" download><i class="fa fa-download"></i> '
    //                         . __('purchase.download_document') . '</a>';
    //                 }
    //                 return $btn;
    //             })
    //             ->rawColumns(['ref_no', 'amount', 'method', 'action'])
    //             ->make(true);
    //     }

    //     $business_locations = BusinessLocation::forDropdown($business_id);
    //     $suppliers = Contact::suppliersDropdown($business_id, false);

    //     return view('report.purchase_payment_report', compact('business_locations', 'suppliers'));
    // }


    /**
     * Shows sell payment report
     *
     * @return \Illuminate\Http\Response
     */
    // old query
    // public function sellPaymentReport(Request $request)
    // {
    //     if (!auth()->user()->can('purchase_n_sell_report.view')) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     $business_id = $request->session()->get('user.business_id');
    //     if ($request->ajax()) {
    //         try {
    //             $customer_id = $request->get('supplier_id', null);
    //             $contact_filter1 = !empty($customer_id) ? "AND t.contact_id=$customer_id" : '';
    //             $contact_filter2 = !empty($customer_id) ? "AND transactions.contact_id=$customer_id" : '';

    //             $query = TransactionPayment::leftjoin('transactions as t', function ($join) use ($business_id) {
    //                 $join->on('transaction_payments.transaction_id', '=', 't.id')
    //                     ->where('t.business_id', $business_id)
    //                     ->whereIn('t.type', ['sell', 'opening_balance']);
    //             })
    //                 ->leftjoin('contacts as c', 't.contact_id', '=', 'c.id')
    //                 ->where('transaction_payments.business_id', $business_id)
    //                 ->where(function ($q) use ($business_id, $contact_filter1, $contact_filter2) {
    //                     $q->whereRaw("(transaction_payments.transaction_id IS NOT NULL AND t.type IN ('sell', 'opening_balance') AND transaction_payments.parent_id IS NULL $contact_filter1)")
    //                         ->orWhereRaw("EXISTS(SELECT * FROM transaction_payments as tp JOIN transactions ON tp.transaction_id = transactions.id WHERE transactions.type IN ('sell', 'opening_balance') AND transactions.business_id = $business_id AND tp.parent_id=transaction_payments.id $contact_filter2)");
    //                 })
    //                 ->select(
    //                     DB::raw("IF(transaction_payments.transaction_id IS NULL,
    //                                 (SELECT c.name FROM transactions as ts
    //                                 JOIN contacts as c ON ts.contact_id=c.id
    //                                 WHERE ts.id=(
    //                                         SELECT tps.transaction_id FROM transaction_payments as tps
    //                                         WHERE tps.parent_id=transaction_payments.id LIMIT 1
    //                                     )
    //                                 ),
    //                                 (SELECT c.name FROM transactions as ts JOIN
    //                                     contacts as c ON ts.contact_id=c.id
    //                                     WHERE ts.id=t.id
    //                                 )
    //                             ) as customer"),
    //                     'transaction_payments.amount',
    //                     'method',
    //                     'paid_on',
    //                     'transaction_payments.payment_ref_no',
    //                     'transaction_payments.document',
    //                     't.invoice_no',
    //                     't.id as transaction_id',
    //                     'cheque_number',
    //                     'card_transaction_number',
    //                     'bank_account_number',
    //                     'transaction_payments.id as DT_RowId'
    //                 )
    //                 ->groupBy('transaction_payments.id');

    //             $start_date = $request->get('start_date');
    //             $end_date = $request->get('end_date');
    //             if (!empty($start_date) && !empty($end_date)) {
    //                 $query->whereBetween(DB::raw('date(paid_on)'), [$start_date, $end_date]);
    //             }

    //             $permitted_locations = auth()->user()->permitted_locations();
    //             if ($permitted_locations != 'all') {
    //                 $query->whereIn('t.location_id', $permitted_locations);
    //             }

    //             $location_id = $request->get('location_id', null);
    //             if (!empty($location_id)) {
    //                 $query->where('t.location_id', $location_id);
    //             }
    //             return Datatables::of($query)
    //                 ->editColumn('invoice_no', function ($row) {
    //                     if (!empty($row->transaction_id)) {
    //                         return '<a data-href="' . route('sells.show', [$row->transaction_id])
    //                             . '" href="#" data-container=".view_modal" class="btn-modal">' . $row->invoice_no . '</a>';
    //                     } else {
    //                         return '';
    //                     }
    //                 })
    //                 ->editColumn('paid_on', '@format_date($paid_on)')
    //                 // ->editColumn('paid_on', 'sd') //please replace the actual data
    //                 ->editColumn('method', function ($row) {
    //                     $method = __('lang_v1.' . $row->method);
    //                     if ($row->method == 'cheque') {
    //                         $method .= '<br>(' . __('lang_v1.cheque_no') . ': ' . $row->cheque_number . ')';
    //                     } elseif ($row->method == 'card') {
    //                         $method .= '<br>(' . __('lang_v1.card_transaction_no') . ': ' . $row->card_transaction_number . ')';
    //                     } elseif ($row->method == 'bank_transfer') {
    //                         $method .= '<br>(' . __('lang_v1.bank_account_no') . ': ' . $row->bank_account_number . ')';
    //                     } elseif ($row->method == 'custom_pay_1') {
    //                         $method = __('lang_v1.custom_payment_1') . '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
    //                     } elseif ($row->method == 'custom_pay_2') {
    //                         $method = __('lang_v1.custom_payment_2') . '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
    //                     } elseif ($row->method == 'custom_pay_3') {
    //                         $method = __('lang_v1.custom_payment_3') . '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
    //                     } elseif ($row->method == 'points') {
    //                         $method = 'Points';
    //                     }
    //                     return $method;
    //                 })
    //                 ->editColumn('amount', function ($row) {
    //                     return '<span class="display_currency paid-amount" data-orig-value="' . $row->amount . '" data-currency_symbol = true>' . $row->amount . '</span>';
    //                 })
    //                 ->addColumn('action', '<button type="button" class="btn btn-primary btn-xs view_payment" data-href="{{ route("payments.viewPayment", [$DT_RowId]) }}">@lang("messages.view")
    //                     </button> @if(!empty($document))<a href="{{asset("/uploads/documents/" . $document)}}" class="btn btn-success btn-xs" download=""><i class="fa fa-download"></i> @lang("purchase.download_document")</a>@endif')
    //                 ->rawColumns(['invoice_no', 'amount', 'method', 'action'])
    //                 ->make(true);
    //         } catch (\Throwable $th) {
    //             throw $th;
    //         }
    //     }
    //     $business_locations = BusinessLocation::forDropdown($business_id);
    //     $customers = Contact::customersDropdown($business_id, false);

    //     return view('report.sell_payment_report')
    //         ->with(compact('business_locations', 'customers'));
    // }

    // optimize query
    public function sellPaymentReport(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        if ($request->ajax()) {
            try {
                $customer_id = $request->get('supplier_id');
                $location_id = $request->get('location_id');
                $start_date = $request->get('start_date');
                $end_date = $request->get('end_date');
                $permitted_locations = auth()->user()->permitted_locations();

                $query = TransactionPayment::leftJoin('transactions as t', function ($join) use ($business_id) {
                    $join->on('transaction_payments.transaction_id', '=', 't.id')
                        ->where('t.business_id', $business_id)
                        ->whereIn('t.type', ['sell', 'opening_balance']);
                })
                    ->leftJoin('contacts as c', 't.contact_id', '=', 'c.id')
                    ->where('transaction_payments.business_id', $business_id)
                    ->where(function ($q) use ($business_id, $customer_id) {
                        $q->whereRaw("transaction_payments.transaction_id IS NOT NULL AND t.type IN ('sell', 'opening_balance') AND transaction_payments.parent_id IS NULL")
                            ->when($customer_id, function ($query) use ($customer_id) {
                                return $query->where('t.contact_id', $customer_id);
                            })
                            ->orWhereRaw(
                                "EXISTS (
                              SELECT 1 FROM transaction_payments as tp
                              JOIN transactions ON tp.transaction_id = transactions.id
                              WHERE transactions.type IN ('sell', 'opening_balance')
                              AND transactions.business_id = ?
                              AND tp.parent_id = transaction_payments.id" .
                                    ($customer_id ? " AND transactions.contact_id = ?" : "") . ")",
                                $customer_id ? [$business_id, $customer_id] : [$business_id]
                            );
                    })
                    ->when(
                        $start_date && $end_date,
                        fn($q) =>
                        $q->whereBetween(DB::raw('date(paid_on)'), [$start_date, $end_date])
                    )
                    ->when(
                        $permitted_locations !== 'all',
                        fn($q) =>
                        $q->whereIn('t.location_id', $permitted_locations)
                    )
                    ->when(
                        $location_id,
                        fn($q) =>
                        $q->where('t.location_id', $location_id)
                    )
                    ->select([
                        DB::raw("IF(transaction_payments.transaction_id IS NULL,
                            (SELECT c.name FROM transactions as ts
                                JOIN contacts as c ON ts.contact_id=c.id
                                WHERE ts.id=(SELECT tps.transaction_id FROM transaction_payments as tps
                                    WHERE tps.parent_id=transaction_payments.id LIMIT 1)
                            ),
                            (SELECT c.name FROM transactions as ts
                                JOIN contacts as c ON ts.contact_id=c.id
                                WHERE ts.id=t.id
                            )
                        ) as customer"),
                        'transaction_payments.amount',
                        'method',
                        'paid_on',
                        'transaction_payments.payment_ref_no',
                        'transaction_payments.document',
                        't.invoice_no',
                        't.id as transaction_id',
                        'cheque_number',
                        'card_transaction_number',
                        'bank_account_number',
                        'transaction_payments.id as DT_RowId'
                    ])
                    ->groupBy('transaction_payments.id');

                return Datatables::of($query)
                    ->editColumn('invoice_no', function ($row) {
                        return !empty($row->transaction_id)
                            ? '<a data-href="' . route('sells.show', [$row->transaction_id])
                            . '" href="#" data-container=".view_modal" class="btn-modal">'
                            . $row->invoice_no . '</a>'
                            : '';
                    })
                    ->editColumn('paid_on', '@format_date($paid_on)')
                    ->editColumn('method', function ($row) {
                        $method = __('lang_v1.' . $row->method);
                        $details = [
                            'cheque' => __('lang_v1.cheque_no') . ': ' . $row->cheque_number,
                            'card' => __('lang_v1.card_transaction_no') . ': ' . $row->card_transaction_number,
                            'bank_transfer' => __('lang_v1.bank_account_no') . ': ' . $row->bank_account_number,
                            'custom_pay_1' => __('lang_v1.custom_payment_1') . '<br>(' . __('lang_v1.transaction_no') . ': ' . ($row->transaction_no ?? '') . ')',
                            'custom_pay_2' => __('lang_v1.custom_payment_2') . '<br>(' . __('lang_v1.transaction_no') . ': ' . ($row->transaction_no ?? '') . ')',
                            'custom_pay_3' => __('lang_v1.custom_payment_3') . '<br>(' . __('lang_v1.transaction_no') . ': ' . ($row->transaction_no ?? '') . ')',
                            'points' => 'Points',
                        ];

                        return isset($details[$row->method])
                            ? $method . '<br>(' . $details[$row->method] . ')'
                            : $method;
                    })
                    ->editColumn('amount', function ($row) {
                        return '<span class="display_currency paid-amount" data-orig-value="' . $row->amount . '" data-currency_symbol="true">' . $row->amount . '</span>';
                    })
                    ->addColumn('action', function ($row) {
                        $downloadBtn = !empty($row->document)
                            ? '<a href="' . asset("/uploads/documents/" . $row->document) . '" class="btn btn-success btn-xs" download=""><i class="fa fa-download"></i> ' . __('purchase.download_document') . '</a>'
                            : '';
                        return '<button type="button" class="btn btn-primary btn-xs view_payment" data-href="' . route("payments.viewPayment", [$row->DT_RowId]) . '">' . __('messages.view') . '</button> ' . $downloadBtn;
                    })
                    ->rawColumns(['invoice_no', 'amount', 'method', 'action'])
                    ->make(true);
            } catch (\Throwable $th) {
                report($th);
                return response()->json(['error' => 'Something went wrong.'], 500);
            }
        }

        $business_locations = BusinessLocation::forDropdown($business_id);
        $customers = Contact::customersDropdown($business_id, false);

        return view('report.sell_payment_report', compact('business_locations', 'customers'));
    }







    /**
     * Shows tables report
     *
     * @return \Illuminate\Http\Response
     */
    public function getTableReport(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        if ($request->ajax()) {
            $query = ResTable::leftjoin('transactions AS T', 'T.res_table_id', '=', 'res_tables.id')
                ->where('T.business_id', $business_id)
                ->where('T.type', 'sell')
                ->where('T.status', 'final')
                ->groupBy('res_tables.id')
                ->select(DB::raw("SUM(final_total) as total_sell"), 'res_tables.name as table');

            $location_id = $request->get('location_id', null);
            if (!empty($location_id)) {
                $query->where('T.location_id', $location_id);
            }

            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
            }

            return Datatables::of($query)
                ->editColumn('total_sell', function ($row) {
                    return '<span class="display_currency" data-currency_symbol="true">' . $row->total_sell . '</span>';
                })
                ->rawColumns(['total_sell'])
                ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.table_report')
            ->with(compact('business_locations'));
    }

    /**
     * Shows service staff report
     *
     * @return \Illuminate\Http\Response
     */
    public function getServiceStaffReport(Request $request)
    {
        if (!auth()->user()->can('sales_representative.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        $waiters = $this->transactionUtil->serviceStaffDropdown($business_id);

        return view('report.service_staff_report')
            ->with(compact('business_locations', 'waiters'));
    }

    /**
     * Shows product sell report grouped by date
     *
     * @return \Illuminate\Http\Response
     */
    public function getproductSellGroupedReport(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        $location_id = $request->get('location_id', null);

        $vld_str = '';
        if (!empty($location_id)) {
            $vld_str = "AND vld.location_id=$location_id";
        }

        if ($request->ajax()) {
            $variation_id = $request->get('variation_id', null);
            $query = TransactionSellLine::join(
                'transactions as t',
                'transaction_sell_lines.transaction_id',
                '=',
                't.id'
            )
                ->join(
                    'variations as v',
                    'transaction_sell_lines.variation_id',
                    '=',
                    'v.id'
                )
                ->join('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
                ->join('products as p', 'pv.product_id', '=', 'p.id')
                ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
                ->where('t.business_id', $business_id)
                ->where('t.type', 'sell')
                ->where('t.status', 'final')
                ->select(
                    'p.name as product_name',
                    'p.enable_stock',
                    'p.type as product_type',
                    'pv.name as product_variation',
                    'v.name as variation_name',
                    't.id as transaction_id',
                    't.transaction_date as transaction_date',
                    DB::raw('DATE_FORMAT(t.transaction_date, "%Y-%m-%d") as formated_date'),
                    DB::raw("(SELECT SUM(vld.qty_available) FROM variation_location_details as vld WHERE vld.variation_id=v.id $vld_str) as current_stock"),
                    DB::raw('SUM(transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) as total_qty_sold'),
                    'u.short_name as unit',
                    DB::raw('SUM((transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) * transaction_sell_lines.unit_price_inc_tax) as subtotal')
                )
                ->groupBy('v.id')
                ->groupBy('formated_date');

            if (!empty($variation_id)) {
                $query->where('transaction_sell_lines.variation_id', $variation_id);
            }
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            if (!empty($location_id)) {
                $query->where('t.location_id', $location_id);
            }

            $customer_id = $request->get('customer_id', null);
            if (!empty($customer_id)) {
                $query->where('t.contact_id', $customer_id);
            }

            return Datatables::of($query)
                ->editColumn('product_name', function ($row) {
                    $product_name = $row->product_name;
                    if ($row->product_type == 'variable') {
                        $product_name .= ' - ' . $row->product_variation . ' - ' . $row->variation_name;
                    }

                    return $product_name;
                })
                ->editColumn('transaction_date', '@format_date($formated_date)')
                ->editColumn('total_qty_sold', function ($row) {
                    return '<span data-is_quantity="true" class="display_currency sell_qty" data-currency_symbol=false data-orig-value="' . (float)$row->total_qty_sold . '" data-unit="' . $row->unit . '" >' . (float) $row->total_qty_sold . '</span> ' . $row->unit;
                })
                ->editColumn('current_stock', function ($row) {
                    if ($row->enable_stock) {
                        return '<span data-is_quantity="true" class="display_currency current_stock" data-currency_symbol=false data-orig-value="' . (float)$row->current_stock . '" data-unit="' . $row->unit . '" >' . (float) $row->current_stock . '</span> ' . $row->unit;
                    } else {
                        return '';
                    }
                })
                ->editColumn('subtotal', function ($row) {
                    return '<span class="display_currency row_subtotal" data-currency_symbol = true data-orig-value="' . $row->subtotal . '">' . $row->subtotal . '</span>';
                })

                ->rawColumns(['current_stock', 'subtotal', 'total_qty_sold'])
                ->make(true);
        }
    }

    /**
     * Shows product stock details and allows to adjust mismatch
     *
     * @return \Illuminate\Http\Response
     */
    public function productStockDetails()
    {
        if (!auth()->user()->can('report.stock_details')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $stock_details = [];
        $location = null;
        $total_stock_calculated = 0;
        if (!empty(request()->input('location_id'))) {
            $variation_id = request()->get('variation_id', null);
            $location_id = request()->input('location_id');

            $location = BusinessLocation::where('business_id', $business_id)
                ->where('id', $location_id)
                ->first();

            $query = Variation::leftjoin('products as p', 'p.id', '=', 'variations.product_id')
                ->leftjoin('units', 'p.unit_id', '=', 'units.id')
                ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                ->leftjoin('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                ->where('p.business_id', $business_id)
                ->where('vld.location_id', $location_id);
            if (!is_null($variation_id)) {
                $query->where('variations.id', $variation_id);
            }

            $stock_details = $query->select(
                DB::raw("(SELECT SUM(COALESCE(TSL.quantity, 0)) FROM transactions
                        LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id
                        WHERE transactions.status='final' AND transactions.type='sell' AND transactions.location_id=$location_id
                        AND TSL.variation_id=variations.id) as total_sold"),
                DB::raw("(SELECT SUM(COALESCE(TSL.quantity_returned, 0)) FROM transactions
                        LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id
                        WHERE transactions.status='final' AND transactions.type='sell' AND transactions.location_id=$location_id
                        AND TSL.variation_id=variations.id) as total_sell_return"),
                DB::raw("(SELECT SUM(COALESCE(TSL.quantity,0)) FROM transactions
                        LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id
                        WHERE transactions.status='final' AND transactions.type='sell_transfer' AND transactions.location_id=$location_id
                        AND TSL.variation_id=variations.id) as total_sell_transfered"),
                DB::raw("(SELECT SUM(COALESCE(PL.quantity,0)) FROM transactions
                        LEFT JOIN purchase_lines AS PL ON transactions.id=PL.transaction_id
                        WHERE transactions.status='received' AND transactions.type='purchase_transfer' AND transactions.location_id=$location_id
                        AND PL.variation_id=variations.id) as total_purchase_transfered"),
                DB::raw("(SELECT SUM(COALESCE(SAL.quantity, 0)) FROM transactions
                        LEFT JOIN stock_adjustment_lines AS SAL ON transactions.id=SAL.transaction_id
                        WHERE transactions.status='received' AND transactions.type='stock_adjustment' AND transactions.location_id=$location_id
                        AND SAL.variation_id=variations.id) as total_adjusted"),
                DB::raw("(SELECT SUM(COALESCE(PL.quantity, 0)) FROM transactions
                        LEFT JOIN purchase_lines AS PL ON transactions.id=PL.transaction_id
                        WHERE transactions.status='received' AND transactions.type='purchase' AND transactions.location_id=$location_id
                        AND PL.variation_id=variations.id) as total_purchased"),
                DB::raw("(SELECT SUM(COALESCE(PL.quantity_returned, 0)) FROM transactions
                        LEFT JOIN purchase_lines AS PL ON transactions.id=PL.transaction_id
                        WHERE transactions.status='received' AND transactions.type='purchase' AND transactions.location_id=$location_id
                        AND PL.variation_id=variations.id) as total_purchase_return"),
                DB::raw("(SELECT SUM(COALESCE(PL.quantity, 0)) FROM transactions
                        LEFT JOIN purchase_lines AS PL ON transactions.id=PL.transaction_id
                        WHERE transactions.status='received' AND transactions.type='opening_stock' AND transactions.location_id=$location_id
                        AND PL.variation_id=variations.id) as total_opening_stock"),
                DB::raw("SUM(vld.qty_available) as stock"),
                'variations.sub_sku as sub_sku',
                'p.name as product',
                'p.id as product_id',
                'p.type',
                'p.sku as sku',
                'units.short_name as unit',
                'p.enable_stock as enable_stock',
                'variations.sell_price_inc_tax as unit_price',
                'pv.name as product_variation',
                'variations.name as variation_name',
                'variations.id as variation_id'
            )
                ->groupBy('variations.id')
                ->get();

            foreach ($stock_details as $index => $row) {
                $total_sold = $row->total_sold ?: 0;
                $total_sell_return = $row->total_sell_return ?: 0;
                $total_sell_transfered = $row->total_sell_transfered ?: 0;

                $total_purchase_transfered = $row->total_purchase_transfered ?: 0;
                $total_adjusted = $row->total_adjusted ?: 0;
                $total_purchased = $row->total_purchased ?: 0;
                $total_purchase_return = $row->total_purchase_return ?: 0;
                $total_opening_stock = $row->total_opening_stock ?: 0;

                $total_stock_calculated = $total_opening_stock + $total_purchased + $total_purchase_transfered + $total_sell_return
                    - ($total_sold + $total_sell_transfered + $total_adjusted + $total_purchase_return);

                $stock_details[$index]->total_stock_calculated = $total_stock_calculated;
            }
        }

        $business_locations = BusinessLocation::forDropdown($business_id);
        return view('report.product_stock_details')
            ->with(compact('stock_details', 'business_locations', 'location'));
    }

    /**
     * Adjusts stock availability mismatch if found
     *
     * @return \Illuminate\Http\Response
     */
    public function adjustProductStock()
    {
        if (!auth()->user()->can('report.stock_details')) {
            abort(403, 'Unauthorized action.');
        }

        if (
            !empty(request()->input('variation_id'))
            && !empty(request()->input('location_id'))
            && !empty(request()->input('stock'))
        ) {
            $business_id = request()->session()->get('user.business_id');

            $vld = VariationLocationDetails::leftjoin(
                'business_locations as bl',
                'bl.id',
                '=',
                'variation_location_details.location_id'
            )
                ->where('variation_location_details.location_id', request()->input('location_id'))
                ->where('variation_id', request()->input('variation_id'))
                ->where('bl.business_id', $business_id)
                ->select('variation_location_details.*')
                ->first();

            if (!empty($vld)) {
                $vld->qty_available = request()->input('stock');
                $vld->save();
            }
        }

        return redirect()->back()->with(['status' => [
            'success' => 1,
            'msg' => __('lang_v1.updated_succesfully')
        ]]);
    }

    /**
     * Retrieves line orders/sales
     *
     * @return obj
     */
    public function serviceStaffLineOrders()
    {
        $business_id = request()->session()->get('user.business_id');

        $query = TransactionSellLine::leftJoin('transactions as t', 't.id', '=', 'transaction_sell_lines.transaction_id')
            ->leftJoin('variations as v', 'transaction_sell_lines.variation_id', '=', 'v.id')
            ->leftJoin('products as p', 'v.product_id', '=', 'p.id')
            ->leftJoin('units as u', 'p.unit_id', '=', 'u.id')
            ->leftJoin('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
            ->leftJoin('users as ss', 'ss.id', '=', 'transaction_sell_lines.res_service_staff_id')
            ->leftjoin(
                'business_locations AS bl',
                't.location_id',
                '=',
                'bl.id'
            )
            ->where('t.business_id', $business_id)
            ->where('t.type', 'sell')
            ->where('t.status', 'final')
            ->whereNotNull('transaction_sell_lines.res_service_staff_id');


        if (!empty(request()->service_staff_id)) {
            $query->where('transaction_sell_lines.res_service_staff_id', request()->service_staff_id);
        }

        if (request()->has('location_id')) {
            $location_id = request()->get('location_id');
            if (!empty($location_id)) {
                $query->where('t.location_id', $location_id);
            }
        }

        if (!empty(request()->start_date) && !empty(request()->end_date)) {
            $start = request()->start_date;
            $end =  request()->end_date;
            $query->whereDate('t.transaction_date', '>=', $start)
                ->whereDate('t.transaction_date', '<=', $end);
        }

        $query->select(
            'p.name as product_name',
            'p.type as product_type',
            'v.name as variation_name',
            'pv.name as product_variation_name',
            'u.short_name as unit',
            't.id as transaction_id',
            'bl.name as business_location',
            't.transaction_date',
            't.invoice_no',
            'transaction_sell_lines.quantity',
            'transaction_sell_lines.unit_price_before_discount',
            'transaction_sell_lines.line_discount_type',
            'transaction_sell_lines.line_discount_amount',
            'transaction_sell_lines.item_tax',
            'transaction_sell_lines.unit_price_inc_tax',
            DB::raw('CONCAT(COALESCE(ss.first_name, ""), COALESCE(ss.last_name, "")) as service_staff')
        );

        $datatable = Datatables::of($query)
            ->editColumn('product_name', function ($row) {
                $name = $row->product_name;
                if ($row->product_type == 'variable') {
                    $name .= ' - ' . $row->product_variation_name . ' - ' . $row->variation_name;
                }
                return $name;
            })
            ->editColumn(
                'unit_price_inc_tax',
                '<span class="display_currency unit_price_inc_tax" data-currency_symbol="true" data-orig-value="{{$unit_price_inc_tax}}">{{$unit_price_inc_tax}}</span>'
            )
            ->editColumn(
                'item_tax',
                '<span class="display_currency item_tax" data-currency_symbol="true" data-orig-value="{{$item_tax}}">{{$item_tax}}</span>'
            )
            ->editColumn(
                'quantity',
                '<span class="display_currency quantity" data-unit="{{$unit}}" data-currency_symbol="false" data-orig-value="{{$quantity}}">{{$quantity}}</span> {{$unit}}'
            )
            ->editColumn(
                'unit_price_before_discount',
                '<span class="display_currency unit_price_before_discount" data-currency_symbol="true" data-orig-value="{{$unit_price_before_discount}}">{{$unit_price_before_discount}}</span>'
            )
            ->addColumn(
                'total',
                '<span class="display_currency total" data-currency_symbol="true" data-orig-value="{{$unit_price_inc_tax * $quantity}}">{{$unit_price_inc_tax * $quantity}}</span>'
            )
            ->editColumn(
                'line_discount_amount',
                function ($row) {
                    $discount = !empty($row->line_discount_amount) ? $row->line_discount_amount : 0;

                    if (!empty($discount) && $row->line_discount_type == 'percentage') {
                        $discount = $row->unit_price_before_discount * ($discount / 100);
                    }

                    return '<span class="display_currency total-discount" data-currency_symbol="true" data-orig-value="' . $discount . '">' . $discount . '</span>';
                }
            )
            ->editColumn('transaction_date', '@format_date($transaction_date)')

            ->rawColumns(['line_discount_amount', 'unit_price_before_discount', 'item_tax', 'unit_price_inc_tax', 'item_tax', 'quantity', 'total'])
            ->make(true);

        return $datatable;
    }

    public function xReading()
    {

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $xread = XRead::where('business_id', $business_id)
                ->select(['id', 'date', 'starting_invoice', 'ending_invoice', 'total_invoices', 'success_transactions', 'void_transactions', 'sales_amout', 'vatable_amount', 'vat_exempt', 'zero_rated', 'total_vat', 'previous_reading', 'current_sales', 'running_total', 'mac_address']);


            return Datatables::of($xread)
                ->addColumn(
                    'action',
                    '<a href="xreading_print/{{$id}}" class="btn btn-primary btn-xs"><i class=""></i>Print</a>'
                )
                ->removeColumn('id')
                ->editColumn('date', '{{$date}}')
                ->editColumn('starting_invoice', '{{$starting_invoice}}')
                ->editColumn('ending_invoice', '{{$ending_invoice}}')
                ->editColumn('total_invoices', '{{$total_invoices}}')
                ->editColumn('success_transactions', '{{$success_transactions}}')
                ->editColumn('void_transactions', '{{$void_transactions}}')
                ->editColumn('sales_amout', '{{$sales_amout}}')
                ->editColumn('vatable_amount', '{{$vatable_amount}}')
                ->editColumn('vat_exempt', '{{$vat_exempt}}')
                ->editColumn('zero_rated', '{{$zero_rated}}')
                ->editColumn('total_vat', '{{$total_vat}}')
                ->editColumn('previous_reading', '{{$previous_reading}}')
                ->editColumn('current_sales', '{{$current_sales}}')
                ->editColumn('running_total', '{{$running_total}}')
                ->editColumn(
                    'mac_address',
                    function ($xread) {
                        $mac = !empty($xread->mac_address) ? $xread->mac_address : "server";
                        return '<span>' . $mac . '</span>';
                    }
                )
                ->editColumn(
                    'mac_name',
                    function ($xread) {
                        $mac_name = $xread->mac_address;
                        $mac_n = "";
                        if ($mac_name == "60-f6-77-14-0e-a1") {
                            $mac_n = "arvin";
                        } elseif ($mac_name == "6c-62-6d-8b-68-64") {
                            $mac_n = "Cashier 1";
                        } elseif ($mac_name == "6c-62-6d-8b-66-fd") {
                            $mac_n = "Cashier 2";
                        } elseif ($mac_name == "6c-62-6d-6d-93-ff") {
                            $mac_n = "Cashier 3";
                        } elseif ($mac_name == "") {
                            $mac_n = "server";
                        }
                        return '<span>' . $mac_n . '</span>';
                    }
                )
                ->rawColumns(['date', 'starting_invoice', 'ending_invoice', 'total_invoices', 'success_transactions', 'void_transactions', 'sales_amout', 'vatable_amount', 'vat_exempt', 'zero_rated', 'total_vat', 'previous_reading', 'current_sales', 'running_total', 'mac_address', 'mac_name', 'action'])
                ->make(true);
        }

        return view('xz_reports.x_reports');
    }

    public function zReading()
    {

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $zread = ZReading::where('business_id', $business_id)
                ->select(['id', 'start_date', 'end_date', 'starting_invoice', 'ending_invoice', 'total_invoices', 'success_transactions', 'void_transactions', 'sales_amout', 'vatable_amount', 'vat_exempt', 'zero_rated', 'total_vat', 'previous_reading', 'current_sales', 'running_total', 'mac_address']);


            return Datatables::of($zread)
                ->addColumn(

                    'action',
                    '<a href="zreading_print/{{$id}}" class="btn btn-primary btn-xs"><i class=""></i>Print</a>'

                )
                ->removeColumn('id')
                ->editColumn('start_date', '{{$start_date}}')
                ->editColumn('end_date', '{{$end_date}}')
                ->editColumn('starting_invoice', '{{$starting_invoice}}')
                ->editColumn('ending_invoice', '{{$ending_invoice}}')
                ->editColumn('total_invoices', '{{$total_invoices}}')
                ->editColumn('success_transactions', '{{$success_transactions}}')
                ->editColumn('void_transactions', '{{$void_transactions}}')
                ->editColumn('sales_amout', '{{$sales_amout}}')
                ->editColumn('vatable_amount', '{{$vatable_amount}}')
                ->editColumn('vat_exempt', '{{$vat_exempt}}')
                ->editColumn('zero_rated', '{{$zero_rated}}')
                ->editColumn('total_vat', '{{$total_vat}}')
                ->editColumn('previous_reading', '{{$previous_reading}}')
                ->editColumn('current_sales', '{{$current_sales}}')
                ->editColumn('running_total', '{{$running_total}}')
                ->editColumn(
                    'mac_address',
                    function ($zread) {
                        $mac = !empty($zread->mac_address) ? $zread->mac_address : "server";
                        return '<span>' . $mac . '</span>';
                    }
                )
                ->editColumn(
                    'mac_name',
                    function ($zread) {
                        $mac_name = $zread->mac_address;
                        $mac_n = "";
                        if ($mac_name == "60-f6-77-14-0e-a1") {
                            $mac_n = "arvin";
                        } elseif ($mac_name == "6c-62-6d-8b-68-64") {
                            $mac_n = "Cashier 1";
                        } elseif ($mac_name == "6c-62-6d-8b-66-fd") {
                            $mac_n = "Cashier 2";
                        } elseif ($mac_name == "6c-62-6d-6d-93-ff") {
                            $mac_n = "Cashier 3";
                        } elseif ($mac_name == "") {
                            $mac_n = "server";
                        }
                        return '<span>' . $mac_n . '</span>';
                    }
                )
                ->rawColumns(['start_date', 'end_date', 'starting_invoice', 'ending_invoice', 'total_invoices', 'success_transactions', 'void_transactions', 'sales_amout', 'vatable_amount', 'vat_exempt', 'zero_rated', 'total_vat', 'previous_reading', 'current_sales', 'running_total', 'mac_address', 'mac_name', 'action'])
                ->make(true);
        }

        return view('xz_reports.z_reports');
    }

    public function printStockreport()
    {


        if (request()->ajax()) {
            //business id
            $business_id = request()->session()->get('user.business_id');

            //get location name
            $location = BusinessLocation::where('business_id', $business_id)->select('name')->get();

            //get products in product table variation is not included
            $products = Product::leftJoin('variation_location_details as vld', 'products.id', '=', 'vld.product_id')
                ->leftJoin('variations as v', 'products.id', '=', 'v.product_id')
                ->where('products.business_id', $business_id)
                ->where('products.type', '!=', 'modifier')
                ->where('products.is_inactive', 0)
                ->select(
                    'products.id',
                    'products.name as product',
                    'products.sku',
                    'v.sell_price_inc_tax as price',
                    DB::raw('SUM((vld.qty_available)) as total_stock')
                )->groupBy('products.id')->get();

            //total stocks footer
            $t_stock = Product::leftJoin('variation_location_details as vld', 'products.id', '=', 'vld.product_id')
                ->where('products.business_id', $business_id)
                ->where('products.type', '!=', 'modifier')
                ->where('products.is_inactive', 0)
                ->select(
                    DB::raw('SUM((vld.qty_available)) as total_stock')
                )->get();

            $output['html_content'] = view('report.partials.stock_report_print', compact('products', 't_stock', 'location'))->render();

            return $output;
        }

        // $business_id = request()->session()->get('user.business_id');

        //     //get location name
        //     $location = BusinessLocation::where('business_id', $business_id)->select('name')->get();

        //     //get products in product table variation is not included
        //     $products = Product::leftJoin('variation_location_details as vld', 'products.id', '=', 'vld.product_id')
        //         ->leftJoin('variations as v', 'products.id', '=', 'v.product_id')
        //         ->where('products.business_id', $business_id)
        //         ->where('products.type', '!=', 'modifier')
        //         ->where('products.is_inactive', 0)
        //         ->select(
        //             'products.id',
        //             'products.name as product',
        //             'products.sku',
        //             'v.dpp_inc_tax as purchase_price',
        //             'v.sell_price_inc_tax as price',
        //             DB::raw('SUM((vld.qty_available)) as total_stock')
        //         )->groupBy('products.id')->get();

        //     //total stocks footer
        //     $t_stock = Product::leftJoin('variation_location_details as vld', 'products.id', '=', 'vld.product_id')
        //         ->where('products.business_id', $business_id)
        //         ->where('products.type', '!=', 'modifier')
        //         ->where('products.is_inactive', 0)
        //         ->select(
        //             DB::raw('SUM((vld.qty_available)) as total_stock')
        //         )->get();

        //     return view('report.partials.stock_report_print', compact('products','t_stock','location'));

    }
}
