<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Denomination;
use App\Models\ZReading;
use App\Models\Transaction;
use App\Models\VoidTransaction;
use App\Models\TransactionSellLine;
use App\Models\BusinessLocation;
use App\Models\XRead;
use App\Models\Variation;

use App\Utils\CashRegisterUtil;

use DB;

class XZReportController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $cashRegisterUtil;

    /**
     * Constructor
     *
     * @param CashRegisterUtil $cashRegisterUtil
     * @return void
     */
    public function __construct(CashRegisterUtil $cashRegisterUtil)
    {
        $this->cashRegisterUtil = $cashRegisterUtil;
        $this->payment_types = ['cash' => 'Cash', 'card' => 'Card', 'cheque' => 'Cheque', 'bank_transfer' => 'Bank Transfer', 'other' => 'Other'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {


        $business_id = session()->get('user.business_id');
        $user_id = session()->get('user.id');

        $ipAddress = $_SERVER['REMOTE_ADDR'];

        $macAddr = "";


        $arp = `arp -a $ipAddress`;
        $lines = explode("\n", $arp);

        foreach ($lines as $line) {
            $cols = preg_split('/\s+/', trim($line));
            if ($cols[0] == $ipAddress) {

                $macAddr = $cols[1];
            }
        }

        $transaction_count = Transaction::where('business_id', $business_id)
            ->where('created_by', $user_id)
            ->where('type', 'sell')
            ->get();

        $zreading_count = ZReading::where('business_id', $business_id)
            ->where('created_by', $user_id)
            ->get();

        $zc = $zreading_count->count();

        $tc = $transaction_count->count();

        if ($tc == 0) {
            return view('x_z_reports.z_reading_no_trans');
        } else {

            //nakapag z reading na
            $today = date('Y-m-d');
            $zreading_today = ZReading::where('business_id', $business_id)
                ->where('end_date', $today)
                ->where('created_by', $user_id)
                ->get();

            $zt = $zreading_today->count();

            if ($zt == 1) {
                return view('x_z_reports.z_reading_same_trans');
            }


            if ($zc == 0) {


                //for running total
                $transaction_query = Transaction::where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid')
                    ->select(

                        DB::raw('SUM(final_total) as running_total')
                    )
                    ->get();


                //first invoice and date transaction
                $t_q = Transaction::where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid')
                    ->first();

                $f_day = $t_q['transaction_date'];

                //first date formatted
                $format_date = date('Y-m-d', strtotime($f_day));



                //last invoice and date transaction
                $t_q2 = Transaction::where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid')
                    ->latest()
                    ->first();

                //last date formatted
                $l_day = $t_q2['transaction_date'];
                $format_date_last = date('Y-m-d', strtotime($l_day));



                //all transaction
                $transaction_q = Transaction::All()
                    ->where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid');

                //all transaction count
                $transactionCount = $transaction_q->count();

                //for void query
                $void_q = VoidTransaction::All()
                    ->where('created_by', $user_id);

                //for count void query
                $voidCount = $void_q->count();


                //tax id is 1
                $transac_q2 = Transaction::leftjoin('transaction_sell_lines', 'transactions.id', '=', 'transaction_sell_lines.transaction_id')
                    ->where('transactions.business_id', $business_id)
                    ->where('transactions.created_by', $user_id)
                    ->where('transactions.type', 'sell')
                    ->where('transactions.status', 'final')
                    ->where('transactions.payment_status', 'paid')
                    ->where('transaction_sell_lines.tax_id', 1)
                    ->select(
                        DB::raw('SUM((transaction_sell_lines.unit_price * transaction_sell_lines.quantity) ) as vatable'),
                        DB::raw('SUM( (transaction_sell_lines.item_tax * transaction_sell_lines.quantity)  ) as vat')
                    )
                    ->get();
                //tax id is 2
                $transac_q3 = Transaction::leftjoin('transaction_sell_lines', 'transactions.id', '=', 'transaction_sell_lines.transaction_id')
                    ->where('transactions.business_id', $business_id)
                    ->where('transactions.created_by', $user_id)
                    ->where('transactions.type', 'sell')
                    ->where('transactions.status', 'final')
                    ->where('transactions.payment_status', 'paid')
                    ->where('transaction_sell_lines.tax_id', 2)
                    ->select(
                        DB::raw('SUM((transaction_sell_lines.unit_price * transaction_sell_lines.quantity) ) as vat_exempt')
                    )
                    ->get();
                //tax id is 3
                $transac_q4 = Transaction::leftjoin('transaction_sell_lines', 'transactions.id', '=', 'transaction_sell_lines.transaction_id')
                    ->where('transactions.business_id', $business_id)
                    ->where('transactions.created_by', $user_id)
                    ->where('transactions.type', 'sell')
                    ->where('transactions.status', 'final')
                    ->where('transactions.payment_status', 'paid')
                    ->where('transaction_sell_lines.tax_id', 3)
                    ->select(
                        DB::raw('SUM((transaction_sell_lines.unit_price * transaction_sell_lines.quantity) ) as zero_rated')
                    )
                    ->get();


                return view('x_z_reports.create')->with(compact('transaction_query', 'transactionCount', 't_q', 't_q2', 'voidCount', 'format_date', 'format_date_last', 'transac_q2', 'transac_q3', 'transac_q4', 'macAddr'));
            } else {
                //if zreading table have value
                $ipAddress = $_SERVER['REMOTE_ADDR'];

                $macAddr = "";


                $arp = `arp -a $ipAddress`;
                $lines = explode("\n", $arp);

                foreach ($lines as $line) {
                    $cols = preg_split('/\s+/', trim($line));
                    if ($cols[0] == $ipAddress) {

                        $macAddr = $cols[1];
                    }
                }

                $zreading = ZReading::where('business_id', $business_id)
                    ->where('created_by', $user_id)
                    ->latest()
                    ->first();

                $last_day = $zreading['end_date'];

                //first transaction during last z reading
                $add_last = date('Y-m-d', strtotime("+1 day", strtotime($last_day)));
                $date_now = date('Y-m-d');
                //count successfull transaction
                $trans1 = Transaction::where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid')
                    ->whereBetween(DB::raw('date(transaction_date)'), [$add_last, $date_now]);

                $t1 = $trans1->get();

                $z_trancount =  $t1->count();

                if ($z_trancount == 0) {
                    return view('x_z_reports.z_reading_no_trans');
                }

                //trans_2 date format and for zreading start date
                $trans2 = Transaction::where('transaction_date', 'like', $add_last . '%')
                    ->where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid')
                    ->first();

                $s_day = $trans2['transaction_date'];
                $format_date = date('Y-m-d', strtotime($s_day));

                //trans3 format date and for zreading end date
                $trans3 = Transaction::where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid')
                    ->latest()
                    ->first();

                $e_day = $trans3['transaction_date'];
                $format_date_end = date('Y-m-d', strtotime($e_day));


                //count void transaction during last z reading
                $vtrans = VoidTransaction::where('created_by', $user_id)
                    ->whereBetween(DB::raw('date(transaction_date)'), [$add_last, $date_now]);

                $vt = $vtrans->get();

                $vcount = $vt->count();

                //for vatable vat vatexempt zero rated
                //tax id is 1
                $trans_q1 = Transaction::leftjoin('transaction_sell_lines', 'transactions.id', '=', 'transaction_sell_lines.transaction_id')
                    ->where('transactions.business_id', $business_id)
                    ->where('transactions.created_by', $user_id)
                    ->where('transactions.type', 'sell')
                    ->where('transactions.status', 'final')
                    ->where('transactions.payment_status', 'paid')
                    ->where('transaction_sell_lines.tax_id', 1)
                    ->whereBetween(DB::raw('date(transactions.transaction_date)'), [$add_last, $date_now])
                    ->select(
                        DB::raw('SUM(transaction_sell_lines.unit_price * transaction_sell_lines.quantity) as vatable'),
                        DB::raw('SUM(transaction_sell_lines.item_tax * transaction_sell_lines.quantity) as vat')
                    );
                $tq1 = $trans_q1->get();

                //tax id is 2
                $trans_q2 = Transaction::leftjoin('transaction_sell_lines', 'transactions.id', '=', 'transaction_sell_lines.transaction_id')
                    ->where('transactions.business_id', $business_id)
                    ->where('transactions.created_by', $user_id)
                    ->where('transactions.type', 'sell')
                    ->where('transactions.status', 'final')
                    ->where('transactions.payment_status', 'paid')
                    ->where('transaction_sell_lines.tax_id', 2)
                    ->whereBetween(DB::raw('date(transactions.transaction_date)'), [$add_last, $date_now])
                    ->select(
                        DB::raw('SUM(transaction_sell_lines.unit_price * transaction_sell_lines.quantity) as vat_exempt')
                    );
                $tq2 = $trans_q2->get();

                //tax id is 3
                $trans_q3 = Transaction::leftjoin('transaction_sell_lines', 'transactions.id', '=', 'transaction_sell_lines.transaction_id')
                    ->where('transactions.business_id', $business_id)
                    ->where('transactions.created_by', $user_id)
                    ->where('transactions.type', 'sell')
                    ->where('transactions.status', 'final')
                    ->where('transactions.payment_status', 'paid')
                    ->where('transaction_sell_lines.tax_id', 3)
                    ->whereBetween(DB::raw('date(transactions.transaction_date)'), [$add_last, $date_now])
                    ->select(
                        DB::raw('SUM(transaction_sell_lines.unit_price * transaction_sell_lines.quantity) as zero_rated')
                    );
                $tq3 = $trans_q3->get();

                //for previous sales
                $transaction_current = Transaction::where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid')
                    ->whereBetween(DB::raw('date(transaction_date)'), [$add_last, $date_now])
                    ->select(
                        DB::raw('SUM(final_total) as current_sales')
                    );

                $t_cur = $transaction_current->get();

                //for running total
                $transaction_running = Transaction::where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid')
                    ->select(
                        DB::raw('SUM(final_total) as running_total')
                    )
                    ->get();

                return view('x_z_reports.create2')->with(compact('zreading', 'add_last', 'trans1', 'z_trancount', 'trans2', 'trans3', 'format_date', 'format_date_end', 'vcount', 'tq1', 'tq2', 'tq3', 'transaction_running', 't_cur', 'macAddr'));
            }
        }
    }

    public function store(Request $request)
    {

        $business_id = session()->get('user.business_id');

        $user_id = session()->get('user.id');

        $zread = ZReading::create([
            'business_id' => $business_id,
            'created_by' => $user_id,
            'mac_address' => !empty($request['mac_address']) ? $request['mac_address'] : "",
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'starting_invoice' => $request['starting_invoice'],
            'ending_invoice' => $request['ending_invoice'],
            'total_invoices' => $request['total_invoices'],
            'success_transactions' => $request['success_transactions'],
            'void_transactions' => $request['void_transactions'],
            'sales_amout' => $request['sales_amout'],
            'vatable_amount' => $request['vatable_amount'],
            'vat_exempt' => $request['vat_exempt'],
            'zero_rated' => $request['zero_rated'],
            'total_vat' => $request['total_vat'],
            'previous_reading' => $request['previous_reading'],
            'current_sales' => $request['current_sales'],
            'running_total' => $request['running_total'],
            'status' => 'save',

        ]);

        if ($zread == 'save') {
            return $zread;
        } else {
            $print = $this->print_this();
            return $print;
        }
    }

    public function print_this()
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        $macAddr = "";


        $arp = `arp -a $ipAddress`;
        $lines = explode("\n", $arp);

        foreach ($lines as $line) {
            $cols = preg_split('/\s+/', trim($line));
            if ($cols[0] == $ipAddress) {

                $macAddr = $cols[1];
            }
        }

        $business_id = session()->get('user.business_id');
        $user_id = session()->get('user.id');

        $header = BusinessLocation::All()->where('business_id', $business_id);

        $z_print = ZReading::where('created_by', $user_id)
            ->orderBy('id', 'desc')
            ->first();

        return view('x_z_reports.z_reading')->with(compact('z_print', 'header'));
    }

    //for x reading
    public function Xcreate()
    {


        $business_id = session()->get('user.business_id');
        $user_id = session()->get('user.id');


        $ipAddress = $_SERVER['REMOTE_ADDR'];

        $macAddr = "";


        $arp = `arp -a $ipAddress`;
        $lines = explode("\n", $arp);

        foreach ($lines as $line) {
            $cols = preg_split('/\s+/', trim($line));
            if ($cols[0] == $ipAddress) {

                $macAddr = $cols[1];
            }
        }
        $date_now = date('Y-m-d');
        //$date_now = "2021-04-09";
        $transaction_count = Transaction::where('business_id', $business_id)
            ->where('transaction_date', 'like', $date_now . '%')
            ->where('created_by', $user_id)
            ->where('type', 'sell')
            ->get();

        $xreading_count = XRead::where('business_id', $business_id)
            ->where('date', 'like', $date_now . '%')
            ->where('created_by', $user_id)
            ->get();

        $xc = $xreading_count->count();

        $tc = $transaction_count->count();

        $last_xreading = XRead::where('business_id', $business_id)
            ->where('created_by', $user_id)
            ->latest()
            ->first();

        //first_transaction
        $f_trans = Transaction::where('created_by', $user_id)
            ->where('type', 'sell')
            ->where('status', 'final')
            ->where('payment_status', 'paid')
            ->first();


        if ($tc == 0) {

            return view('x_z_reports.z_reading_no_trans');
        } else {
            if (empty($last_xreading)) {
                $open_time = $f_trans->transaction_date;
            } else {
                $open_time = $last_xreading->created_at;
            }
            $close_time = \Carbon::now()->toDateTimeString();

            //$open_time = "2021-04-09 00:01:00";
            //$close_time = "2021-04-09 23:59:00";


            //for running total
            $transaction_query = Transaction::where('created_by', $user_id)
                ->where('type', 'sell')
                ->where('status', 'final')
                ->where('payment_status', 'paid')
                ->select(

                    DB::raw('SUM(final_total) as running_total')
                )
                ->get();

            $date_now = date('Y-m-d');
            //$date_now = "2021-04-09";

            //for current total
            $t_current = Transaction::whereBetween('transaction_date', [$open_time, $close_time])
                ->where('created_by', $user_id)
                ->where('type', 'sell')
                ->where('status', 'final')
                ->where('payment_status', 'paid')
                ->first();
            if (empty($t_current)) {
                return view('x_z_reports.z_reading_no_trans');
            } else {
                //for current total
                $transaction_current = Transaction::whereBetween('transaction_date', [$open_time, $close_time])
                    ->where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid')
                    ->select(

                        DB::raw('SUM(final_total) as current_total')
                    )
                    ->get();

                //first invoice transaction
                $t_q = Transaction::whereBetween('transaction_date', [$open_time, $close_time])
                    ->where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid')
                    ->first();

                //last invoice transaction
                $t_q2 = Transaction::whereBetween('transaction_date', [$open_time, $close_time])
                    ->where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid')
                    ->latest()
                    ->first();



                //all transaction
                $transaction_q = Transaction::whereBetween('transaction_date', [$open_time, $close_time])
                    ->where('created_by', $user_id)
                    ->where('type', 'sell')
                    ->where('status', 'final')
                    ->where('payment_status', 'paid')
                    ->get();

                //all transaction count
                $transactionCount = $transaction_q->count();

                //for void query
                $void_q = VoidTransaction::whereBetween('transaction_date', [$open_time, $close_time])
                    ->where('created_by', $user_id)
                    ->get();

                //for count void query
                $voidCount = $void_q->count();


                //tax id is 1
                $transac_q2 = Transaction::leftjoin('transaction_sell_lines', 'transactions.id', '=', 'transaction_sell_lines.transaction_id')
                    ->whereBetween('transaction_date', [$open_time, $close_time])
                    ->where('transactions.business_id', $business_id)
                    ->where('transactions.created_by', $user_id)
                    ->where('transactions.type', 'sell')
                    ->where('transactions.status', 'final')
                    ->where('transactions.payment_status', 'paid')
                    ->where('transaction_sell_lines.tax_id', 1)
                    ->select(
                        DB::raw('SUM((transaction_sell_lines.unit_price * transaction_sell_lines.quantity) ) as vatable'),
                        DB::raw('SUM( (transaction_sell_lines.item_tax * transaction_sell_lines.quantity)  ) as vat')
                    )
                    ->get();
                //tax id is 2
                $transac_q3 = Transaction::leftjoin('transaction_sell_lines', 'transactions.id', '=', 'transaction_sell_lines.transaction_id')
                    ->whereBetween('transactions.transaction_date', [$open_time, $close_time])
                    ->where('transactions.business_id', $business_id)
                    ->where('transactions.created_by', $user_id)
                    ->where('transactions.type', 'sell')
                    ->where('transactions.status', 'final')
                    ->where('transactions.payment_status', 'paid')
                    ->where('transaction_sell_lines.tax_id', 2)
                    ->select(
                        DB::raw('SUM((transaction_sell_lines.unit_price * transaction_sell_lines.quantity) ) as vat_exempt')
                    )
                    ->get();
                //tax id is 3
                $transac_q4 = Transaction::leftjoin('transaction_sell_lines', 'transactions.id', '=', 'transaction_sell_lines.transaction_id')
                    ->whereBetween('transactions.transaction_date', [$open_time, $close_time])
                    ->where('transactions.business_id', $business_id)
                    ->where('transactions.created_by', $user_id)
                    ->where('transactions.type', 'sell')
                    ->where('transactions.status', 'final')
                    ->where('transactions.payment_status', 'paid')
                    ->where('transaction_sell_lines.tax_id', 3)
                    ->select(
                        DB::raw('SUM((transaction_sell_lines.unit_price * transaction_sell_lines.quantity) ) as zero_rated')
                    )
                    ->get();



                return view('x_z_reports.Xcreate')->with(compact('transaction_query', 'transactionCount', 't_q', 't_q2', 'voidCount', 'transac_q2', 'transac_q3', 'transac_q4', 'macAddr', 'date_now', 'transaction_current'));
            }
        }
    }

    public function store2(Request $request)
    {

        $business_id = session()->get('user.business_id');

        $user_id = session()->get('user.id');

        $xread = XRead::create([
            'business_id' => $business_id,
            'created_by' => $user_id,
            'mac_address' => !empty($request['mac_address']) ? $request['mac_address'] : "",
            'date' => $request['date'],
            'starting_invoice' => $request['starting_invoice'],
            'ending_invoice' => $request['ending_invoice'],
            'total_invoices' => $request['total_invoices'],
            'success_transactions' => $request['success_transactions'],
            'void_transactions' => $request['void_transactions'],
            'sales_amout' => $request['sales_amout'],
            'vatable_amount' => $request['vatable_amount'],
            'vat_exempt' => $request['vat_exempt'],
            'zero_rated' => $request['zero_rated'],
            'total_vat' => $request['total_vat'],
            'previous_reading' => $request['previous_reading'],
            'current_sales' => $request['current_sales'],
            'running_total' => $request['running_total'],
            'status' => 'save',

        ]);

        if ($xread == 'save') {
            return $xread;
        } else {
            $print2 = $this->print_this2();
            return $print2;
        }
    }

    public function print_this2()
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        $macAddr = "";


        $arp = `arp -a $ipAddress`;
        $lines = explode("\n", $arp);

        foreach ($lines as $line) {
            $cols = preg_split('/\s+/', trim($line));
            if ($cols[0] == $ipAddress) {

                $macAddr = $cols[1];
            }
        }
        $date_now = date('Y-m-d');

        $business_id = session()->get('user.business_id');
        $user_id = session()->get('user.id');

        $header = BusinessLocation::All()->where('business_id', $business_id);

        $x_print = XRead::where('created_by', $user_id)
            ->latest()
            ->get();

        return view('x_z_reports.x_print')->with(compact('x_print', 'header'));
    }

    public function print_again($id)
    {

        $id = $id;

        $business_id = session()->get('user.business_id');

        $header = BusinessLocation::All()->where('business_id', $business_id);

        $re_print = XRead::where('id', $id)
            ->get();

        return view('xz_reports.xreading_print')->with(compact('re_print', 'header'));
    }

    public function print_again2($id)
    {

        $id = $id;

        $business_id = session()->get('user.business_id');

        $header = BusinessLocation::All()->where('business_id', $business_id);

        $re_print = ZReading::where('id', $id)
            ->get();

        return view('xz_reports.zreading_print')->with(compact('re_print', 'header'));
    }

    public function update_product_price()
    {

        $variations = Variation::leftjoin('products', 'products.id', '=', 'variations.product_id')
            ->where('tax', 1)
            ->select('variations.id', 'default_purchase_price', 'dpp_inc_tax', 'default_sell_price', 'sell_price_inc_tax')
            ->get();

        foreach ($variations as $variation) {
            $var = Variation::findOrFail($variation->id);

            $dpp = $var->dpp_inc_tax / 1.12;
            $dsp = $var->sell_price_inc_tax / 1.12;

            $var->default_purchase_price = $dpp;
            $var->default_sell_price = $dsp;

            $var->save();
        }

        return 'done';
    }
}
