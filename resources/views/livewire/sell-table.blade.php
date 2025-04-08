<div>
    {{-- The Master doesn't talk, he acts. --}}
    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1>@lang('sale.sells')
            <small></small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        @component('components.filters', ['title' => __('report.filters')])
            @include('sell.partials.sell_list_filters')
            @if ($is_woocommerce)
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="synced_from_woocommerce" name="only_woocommerce_sells" value="1"
                                    class="input-icheck">
                                {{ __('lang_v1.synced_from_woocommerce') }}
                            </label>
                        </div>
                    </div>
                </div>
            @endif
        @endcomponent
        @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_sales')])
            @can('sell.create')
                @slot('tool')
                    <div class="box-tools">
                        <a class="btn btn-block btn-primary" href="{{ route('sells.create') }}">
                            <i class="fa fa-plus"></i> @lang('messages.add')</a>
                    </div>
                @endslot
            @endcan
            @can('direct_sell.access')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped ajax_view" id="">
                        <thead>
                            <tr>
                                <th>@lang('messages.date')</th>
                                <th>@lang('sale.invoice_no')</th>
                                <th>@lang('sale.customer_name')</th>
                                <th>@lang('sale.location')</th>
                                <th>@lang('sale.payment_status')</th>
                                <th>@lang('sale.total_amount')</th>
                                <th>@lang('sale.total_paid')</th>
                                <th>@lang('purchase.payment_due')</th>
                                <th>Vatable</th>
                                <th>Vat@12%</th>
                                <th>Vat Exempt</th>
                                <th>Vat Zero Rated</th>
                                <th>@lang('messages.action')</th>
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
                                    <td>{{ number_format($sale->final_total, 2) }}</td>
                                    <td>{{ number_format($sale->total_paid, 2) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray font-17 footer-total text-center">
                                <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                                <td id="footer_payment_status_count"></td>
                                <td><span class="display_currency" id="footer_sale_total" data-currency_symbol ="true"></span>
                                </td>
                                <td><span class="display_currency" id="footer_total_paid" data-currency_symbol ="true"></span>
                                </td>

                                <td class="text-left"><small>@lang('lang_v1.sell_due') - <span class="display_currency"
                                            id="footer_total_remaining"
                                            data-currency_symbol ="true"></span><br>@lang('lang_v1.sell_return_due') - <span
                                            class="display_currency" id="footer_total_sell_return_due"
                                            data-currency_symbol ="true"></span></small></td>
                                <td><span class="display_currency" id="footer_vatable" data-currency_symbol ="true"></span></td>
                                <td><span class="display_currency" id="footer_vat" data-currency_symbol ="true"></span></td>
                                <td><span class="display_currency" id="footer_vat_exempt" data-currency_symbol ="true"></span>
                                </td>
                                <td><span class="display_currency" id="footer_vzr" data-currency_symbol ="true"></span></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    {{ $sales->links() }}
                </div>
            @endcan
        @endcomponent
    </section>
    <!-- /.content -->
</div>
