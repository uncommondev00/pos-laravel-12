<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    <!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang( 'Void Transaction')
        <small></small>
    </h1>
</section>


<!-- Main content -->
<section class="content no-print">
    @component('components.filters', ['title' => __('report.filters')])
        @include('sell.partials.void_filters')
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
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'Void Transaction')])
        @can('sell.view')
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
                             <th>MAC Address</th>
                             <th>IP Address</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sells as $sale)
                            <tr>
                                <td>@format_date($sale->transaction_date)</td>
                                <td>{{ $sale->invoice_no }}</td>
                                <td>{{ $sale->contact_name }}</td>
                                <td>{{ $sale->business_location }}</td>
                                <td>
                                    <span class="label label-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                        {{ __('lang_v1.' . $sale->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="display_currency" data-currency_symbol="true">{{ $sale->final_total }}</span>
                                </td>
                                <td>{{ $sale->mac_address }}</td>
                                <td>{{ $sale->ip_address }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">@lang('purchase.no_records_found')</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray font-17 footer-total text-center">
                            <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                            <td id="footer_payment_status_count"></td>
                            <td><span class="display_currency" id="footer_sale_total" data-currency_symbol ="true"></span></td>
                            <td></td>
                            <td></td>
                            
                        </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class="col-sm-12">
                        {{ $sells->links() }}
                    </div>
                </div>
            </div>
        @endcan
    @endcomponent
</section>
<!-- /.content -->
<div class="modal fade payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<!-- This will be printed -->
<!-- <section class="invoice print_section" id="receipt_section">
</section> -->
</div>
