<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Restaurant\ResTable;

use App\Models\BusinessLocation;
use App\Models\Transaction;
use App\Models\User;

use App\Utils\Util;

class DataController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;

    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    /**
     * Show the restaurant module related details in pos screen.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPosDetails(Request $request)
    {
        if (request()->ajax()) {
            $business_id = $request->session()->get('user.business_id');
            $location_id = $request->get('location_id');
            if (!empty($location_id)) {
                $transaction_id = $request->get('transaction_id', null);
                if (!empty($transaction_id)) {
                    $transaction = Transaction::find($transaction_id);
                    $view_data = ['res_table_id' => $transaction->res_table_id,
                            'res_waiter_id' => $transaction->res_waiter_id,
                        ];
                } else {
                    $view_data = ['res_table_id' => null, 'res_waiter_id' => null];
                }

                

                $waiters_enabled = false;
                $tables_enabled = false;
                $waiters = null;
                $tables = null;
                if ($this->commonUtil->isModuleEnabled('service_staff')) {
                    $waiters_enabled = true;
                    $waiters = $this->commonUtil->serviceStaffDropdown($business_id);
                }
                if ($this->commonUtil->isModuleEnabled('tables')) {
                    $tables_enabled = true;
                    $tables = ResTable::where('business_id', $business_id)
                            ->where('location_id', $location_id)
                            ->pluck('name', 'id');
                }
            } else {
                $tables = [];
                $waiters = [];
                $waiters_enabled = $this->commonUtil->isModuleEnabled('service_staff') ? true : false;
                $tables_enabled = $this->commonUtil->isModuleEnabled('tables') ? true : false;
                $view_data = ['res_table_id' => null, 'res_waiter_id' => null];
            }

            return view('restaurant.partials.pos_table_dropdown')
                    ->with(compact('tables', 'waiters', 'view_data', 'waiters_enabled', 'tables_enabled'));
        }
    }

    /**
     * Save the pos screen details.
     *
     * @return null
     */
    public function sellPosStore($input)
    {
        $table_id = request()->get('res_table_id');
        $res_waiter_id = request()->get('res_waiter_id');

        Transaction::where('id', $input['transaction_id'])
            ->where('type', 'sell')
            ->where('business_id', $input['business_id'])
            ->update(['res_table_id' => $table_id,
                'res_waiter_id' => $res_waiter_id]);
    }
}
