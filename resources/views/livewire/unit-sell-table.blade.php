<div>
    {{-- Do your work, then step back. --}}
    <!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang( 'Per Unit Sales')
        <small></small>
    </h1>
</section>


<!-- Main content -->
<section class="content no-print">
    @component('components.filters', ['title' => __('report.filters')])
        @include('sell.partials.sell_list_filters')
        @if($is_woocommerce)
            <div class="col-md-4">
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                          <input type="checkbox" wire:model="is_woocommerce" class="input-icheck">
                          Woocommerce
                        </label>
                    </div>
                </div>
            </div>
        @endif
    @endcomponent
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'Per Unit Sales')])
        @can('sell.view')
            <div class="table-responsive">
                @include('includes.table-controls', [
                       'perPageOptions' => $perPageOptions,
                       'search' => $search
                   ])
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
                             <th>MAC Address</th>
                             <th>IP Address</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sells as $sale)
                            <tr>
                                <td>{{ $sale->invoice_no }}</td>
                                <td>@format_date($sale->transaction_date)</td>
                                <td>{{ $sale->contact_name }}</td>
                                <td>{{ $sale->business_location }}</td>
                                <td>
                                    <span class="display_currency" data-currency_symbol="true">{{ $sale->final_total }}</span>
                                </td>
                                <td>
                                    <span class="label label-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                        {{ __('lang_v1.' . $sale->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            @lang('sale.action')
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="{{ action('SellController@show', [$sale->id]) }}"><i class="fa fa-eye"></i> View</a></li>
                                            <li><a href="{{ action('SellController@edit', [$sale->id]) }}"><i class="fa fa-edit"></i> Edit</a></li>
                                            <li></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">@lang('purchase.no_records_found')</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray font-17 footer-total text-center">
                            <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                            <td id="footer_payment_status_count"></td>
                            <td><span class="display_currency" id="footer_sale_total" data-currency_symbol ="true"></span></td>
                            <td><span class="display_currency" id="footer_total_paid" data-currency_symbol ="true"></span></td>

                            <td class="text-left"><small>@lang('lang_v1.sell_due') - <span class="display_currency" id="footer_total_remaining" data-currency_symbol ="true"></span><br>@lang('lang_v1.sell_return_due') - <span class="display_currency" id="footer_total_sell_return_due" data-currency_symbol ="true"></span></small></td>
                            <td><span class="display_currency" id="footer_vatable" data-currency_symbol ="true"></span></td>
                            <td><span class="display_currency" id="footer_vat" data-currency_symbol ="true"></span></td>
                            <td><span class="display_currency" id="footer_vat_exempt" data-currency_symbol ="true"></span></td>
                            <td><span class="display_currency" id="footer_vzr" data-currency_symbol ="true"></span></td>
                            <td></td>
                            <td></td>
                            
                        </tr>
                    </tfoot>
                </table>
                
            </div>
            @include('includes.pagination', ['paginator' => $sells])
        @endcan
    @endcomponent
</section>
</div>
