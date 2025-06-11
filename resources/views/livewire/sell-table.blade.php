<div>
    {{-- The Master doesn't talk, he acts. --}}
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
                    @include('includes.table-controls', [
                            'perPageOptions' => $perPageOptions,
                            'search' => $search,
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
                                <th>@lang('messages.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $sale->transaction_date }}</td>
                                    <td>{{ $sale->invoice_no }}</td>
                                    <td>{{ $sale->contact->name ?? 'test' }}</td>
                                    <td>{{ $sale->location->name ?? 'test' }}</td>
                                    <td>{{ ucfirst($sale->payment_status) }}</td>
                                    <td>{{ number_format($sale->final_total, 2) }}</td>
                                    <td>{{ number_format($sale->total_paid, 2) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                                                    data-toggle="dropdown" 
                                                    aria-expanded="false">
                                                @lang('messages.actions')
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                @if(auth()->user()->can("sell.view") || auth()->user()->can("direct_sell.access"))
                                                    <li>
                                                        <a href="#" 
                                                           data-href="{{ route('sells.show', [$sale->id]) }}" 
                                                           class="btn-modal" 
                                                           data-container=".view_modal">
                                                            <i class="fa fa-external-link" aria-hidden="true"></i> @lang('messages.view')
                                                        </a>
                                                    </li>
                                                @endif
                                        
                                                @if($sale->is_direct_sale == 0)
                                                    @can('sell.update')
                                                        <li>
                                                            <a target="_blank" href="{{ route('sells.edit', [$sale->id]) }}">
                                                                <i class="glyphicon glyphicon-edit"></i> @lang('messages.edit')
                                                            </a>
                                                        </li>
                                                    @endcan
                                                @else
                                                    @can('direct_sell.access')
                                                        <li>
                                                            <a target="_blank" href="{{ route('sells.edit', [$sale->id]) }}">
                                                                <i class="glyphicon glyphicon-edit"></i> @lang('messages.edit')
                                                            </a>
                                                        </li>
                                                    @endcan
                                                @endif
                                        
                                                @if(request()->session()->get('user.id') == 1)
                                                    @can('direct_sell.delete')
                                                        <li>
                                                            <a href="{{ route('sells.destroy', [$sale->id]) }}" 
                                                               class="delete-sale">
                                                                <i class="fa fa-trash"></i> @lang('messages.delete')
                                                            </a>
                                                        </li>
                                                    @endcan
                                                @endif
                                        
                                                @if(auth()->user()->can("sell.view") || auth()->user()->can("direct_sell.access"))
                                                    <li>
                                                        <a href="#" 
                                                           class="print-invoice" 
                                                            data-href="{{ route('pos.printInvoice', [$sale->id]) }}">
                                                            <i class="fa fa-print" aria-hidden="true"></i> @lang('messages.print')
                                                        </a>
                                                    </li>
                                                @endif
                                        
                                                <li class="divider"></li>
                                        
                                                @if($sale->payment_status != "paid" && (auth()->user()->can("sell.create") || auth()->user()->can("direct_sell.access")))
                                                    <li>
                                                        <a href="{{ route('payments.addPayment', [$sale->id]) }}" 
                                                           class="add_payment_modal">
                                                            <i class="fa fa-money"></i> @lang('purchase.add_payment')
                                                        </a>
                                                    </li>
                                                @endif
                                        
                                                <li>
                                                    <a href="{{ route('payments.show', [$sale->id]) }}" 
                                                       class="view_payment_modal">
                                                        <i class="fa fa-money"></i> @lang('purchase.view_payments')
                                                    </a>
                                                </li>
                                        
                                                @can('sell.create')
                                                    <li>
                                                        <a href="{{ route('sells.duplicateSell', [$sale->id]) }}">
                                                            <i class="fa fa-copy"></i> @lang('lang_v1.duplicate_sell')
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('sell-return.add', [$sale->id]) }}">
                                                            <i class="fa fa-undo"></i> @lang('lang_v1.sell_return')
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('pos.showInvoiceUrl', [$sale->id]) }}" 
                                                           class="view_invoice_url">
                                                            <i class="fa fa-external-link"></i> @lang('lang_v1.view_invoice_url')
                                                        </a>
                                                    </li>
                                                @endcan
                                        
                                                @can('send_notification')
                                                    <li>
                                                        <a href="#" 
                                                           data-href="{{ route('notification.getTemplate', ['transaction_id' => $sale->id, 'template_for' => 'new_sale']) }}" 
                                                           class="btn-modal" 
                                                           data-container=".view_modal">
                                                            <i class="fa fa-envelope" aria-hidden="true"></i> @lang('lang_v1.new_sale_notification')
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </td>
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
                   
                </div>
                @include('includes.pagination', ['paginator' => $sales])
            @endcan
        @endcomponent
    </section>
    <!-- /.content -->
    
    
</div>
