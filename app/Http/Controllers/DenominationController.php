<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Denomination;

use App\Utils\CashRegisterUtil;

use DB;

class DenominationController extends Controller
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

    public function getDenonimation()
    {
        $register_details =  $this->cashRegisterUtil->getRegisterDetails();

        $user_id = auth()->user()->id;
        $open_time = $register_details['open_time'];
        $close_time = \Carbon::now()->toDateTimeString();
        $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time);

        return view('denomination_print')->with(compact('register_details', 'details'));
    }


    public function openDrawer()
    {

        return view('open');
    }
}
