<div>

    <div class="table-responsive">

        <x-search />


        <table class="table table-bordered table-striped" style="width: 100%;" id="searchTable">
            <thead>
                <tr>
                    <th>@lang('messages.date')</th>
                    <th>@lang('sale.invoice_no')</th>
                    <th>@lang('sale.customer_name')</th>
                    <th>@lang('sale.location')</th>
                    <th>@lang('sale.payment_status')</th>
                    <th>@lang('sale.total_amount')</th>
                    <th>@lang('sale.total_paid')</th>
                    <th>@lang('sale.total_remaining')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->transaction_date }}</td>
                    <td>{{ $sale->invoice_no }}</td>
                    <td>{{ $sale->customer_name }}</td>
                    <td>{{ $sale->business_location }}</td>
                    <td>{{ ucfirst($sale->payment_status) }}</td>
                    <td><span class="display_currency" data-currency_symbol="true">{{ $sale->final_total }}</span></td>
                    <td><span class="display_currency" data-currency_symbol="true">{{ $sale->total_paid }}</span></td>
                    <td><span class="display_currency" data-currency_symbol="true">{{ $sale->total_remaining }}</span></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray font-17 footer-total text-center">
                    <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                    <td id="sr_footer_payment_status_count"></td>
                    <td><span class="display_currency" id="sr_footer_sale_total" data-currency_symbol="true"> {{ $sales->sum('final_total') }}</span></td>
                    <td><span class="display_currency" id="sr_footer_total_paid" data-currency_symbol="true">{{$sales->sum('total_paid')}}</span></td>
                    <td class="text-left">
                        <small>@lang('lang_v1.sell_due') -
                            <span class="display_currency" id="sr_footer_total_remaining" data-currency_symbol="true">{{$sales->sum('total_remaining')}}</span>
                            <br>
                            @lang('lang_v1.sell_return_due') -
                            <span class="display_currency" id="sr_footer_total_sell_return_due" data-currency_symbol="true">{{$sales->sum('sell_return_due')}}</span>
                        </small>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
