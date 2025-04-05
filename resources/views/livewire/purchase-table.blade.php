<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang('purchase.purchases')
        <small></small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content no-print">
    @component('components.filters', ['title' => __('report.filters')])
    <div class="col-md-3">
        <div class="form-group">
            <label for="purchase_list_filter_location_id">{{ __('purchase.business_location') }}:</label>
            <select name="purchase_list_filter_location_id" 
                    id="purchase_list_filter_location_id" 
                    class="form-control select2" 
                    style="width:100%">
                <option value="">{{ __('lang_v1.all') }}</option>
                @foreach($business_locations as $key => $location)
                    <option value="{{ $key }}">{{ $location }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="purchase_list_filter_supplier_id">{{ __('purchase.supplier') }}:</label>
            <select name="purchase_list_filter_supplier_id" 
                    id="purchase_list_filter_supplier_id" 
                    class="form-control select2" 
                    style="width:100%">
                <option value="">{{ __('lang_v1.all') }}</option>
                @foreach($suppliers as $key => $supplier)
                    <option value="{{ $key }}">{{ $supplier }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="purchase_list_filter_status">{{ __('purchase.purchase_status') }}:</label>
            <select name="purchase_list_filter_status" 
                    id="purchase_list_filter_status" 
                    class="form-control select2" 
                    style="width:100%">
                <option value="">{{ __('lang_v1.all') }}</option>
                @foreach($orderStatuses as $key => $status)
                    <option value="{{ $key }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="purchase_list_filter_payment_status">{{ __('purchase.payment_status') }}:</label>
            <select name="purchase_list_filter_payment_status" 
                    id="purchase_list_filter_payment_status" 
                    class="form-control select2" 
                    style="width:100%">
                <option value="">{{ __('lang_v1.all') }}</option>
                <option value="paid">{{ __('lang_v1.paid') }}</option>
                <option value="due">{{ __('lang_v1.due') }}</option>
                <option value="partial">{{ __('lang_v1.partial') }}</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="purchase_list_filter_date_range">{{ __('report.date_range') }}:</label>
            <input type="text" 
                   name="purchase_list_filter_date_range" 
                   id="purchase_list_filter_date_range" 
                   class="form-control" 
                   readonly 
                   placeholder="{{ __('lang_v1.select_a_date_range') }}">
        </div>
    </div>
@endcomponent

    @component('components.widget', ['class' => 'box-primary', 'title' => __('purchase.all_purchases')])
        @can('purchase.create')
            @slot('tool')
                <div class="box-tools">
                    <a class="btn btn-block btn-primary" href="{{route('purchases.create')}}">
                    <i class="fa fa-plus"></i> @lang('messages.add')</a>
                </div>
            @endslot
        @endcan
        @can('purchase.view')
            <div class="table-responsive">
                <input type="text" wire:model.live="search" class="form-control mb-3" placeholder="Search purchase...">
                <table class="table table-bordered table-striped ajax_view" id="purchase-table">
                    <thead>
                        <tr>
                            <th>@lang('messages.date')</th>
                            <th>@lang('purchase.ref_no')</th>
                            <th>@lang('purchase.location')</th>
                            <th>@lang('purchase.supplier')</th>
                            <th>@lang('purchase.purchase_status')</th>
                            <th>@lang('purchase.payment_status')</th>
                            <th>@lang('purchase.grand_total')</th>
                            <th>@lang('purchase.payment_due') &nbsp;&nbsp;<i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="{{ __('messages.purchase_due_tooltip')}}" aria-hidden="true"></i></th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                            <tr>
                                <td>
                                    {!! $purchase->return_exists ? 
                                        $purchase->ref_no . ' <small class="label bg-red label-round no-print"><i class="fa fa-undo"></i></small>' : 
                                        $purchase->ref_no !!}
                                </td>
                                <td>{{ @format_date($purchase->transaction_date) }}</td>
                                <td>{{ $purchase->name }}</td>
                                <td>{{ $purchase->location_name }}</td>
                                <td>
                                    <span class="label @transaction_status($purchase->status) status-label">
                                        {{ __('lang_v1.' . $purchase->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="label @payment_status($purchase->payment_status)">
                                        {{ __('lang_v1.' . $purchase->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="display_currency" data-currency_symbol="true">
                                        {{ $purchase->final_total }}
                                    </span>
                                </td>
                                <td>{!! $this->getPaymentDue($purchase) !!}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                                            {{ __("messages.actions") }}
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                            @can("purchase.view")
                                                <li>
                                                    <a href="#" 
                                                       data-href="{{ route('purchases.show', [$purchase->id]) }}" 
                                                       class="btn-modal" 
                                                       data-container=".view_modal">
                                                        <i class="fa fa-eye" aria-hidden="true"></i> 
                                                        {{ __("messages.view") }}
                                                    </a>
                                                </li>
                                    
                                                @if($purchase->status == 'received')
                                                    <li>
                                                        <a href="#" 
                                                           class="print-invoice" 
                                                           data-href="{{ route('purchases.printInvoice', [$purchase->id]) }}">
                                                            <i class="fa fa-print" aria-hidden="true"></i> 
                                                            {{ __("messages.print") }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endcan
                                    
                                            @can("purchase.update")
                                                @if($purchase->status != 'received')
                                                    <li>
                                                        <a href="{{ route('purchases.edit', [$purchase->id]) }}">
                                                            <i class="glyphicon glyphicon-edit"></i> 
                                                            {{ __("messages.edit") }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endcan
                                    
                                            @can("purchase.delete")
                                                @if($purchase->status != 'received')
                                                    <li>
                                                        <a href="{{ route('purchases.destroy', [$purchase->id]) }}" 
                                                           class="delete-purchase">
                                                            <i class="fa fa-trash"></i> 
                                                            {{ __("messages.delete") }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endcan
                                    
                                            <li>
                                                <a href="{{ route('labels.show') }}?purchase_id={{ $purchase->id }}" 
                                                   data-toggle="tooltip" 
                                                   title="Print Barcode/Label">
                                                    <i class="fa fa-barcode"></i> 
                                                    {{ __('barcode.labels') }}
                                                </a>
                                            </li>
                                    
                                            @can("purchase.view")
                                                @if(!empty($purchase->document))
                                                    @php
                                                        $document_name = !empty(explode("_", $purchase->document, 2)[1]) 
                                                            ? explode("_", $purchase->document, 2)[1] 
                                                            : $purchase->document;
                                                    @endphp
                                                    <li>
                                                        <a href="{{ url('uploads/documents/' . $purchase->document) }}" 
                                                           download="{{ $document_name }}">
                                                            <i class="fa fa-download" aria-hidden="true"></i> 
                                                            {{ __("purchase.download_document") }}
                                                        </a>
                                                    </li>
                                                    @if(isFileImage($document_name))
                                                        <li>
                                                            <a href="#" 
                                                               data-href="{{ url('uploads/documents/' . $purchase->document) }}" 
                                                               class="view_uploaded_document">
                                                                <i class="fa fa-picture-o" aria-hidden="true"></i> 
                                                                {{ __("lang_v1.view_document") }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endcan
                                    
                                            @can("purchase.create")
                                                <li class="divider"></li>
                                                @if($purchase->payment_status != 'paid')
                                                    <li>
                                                        <a href="{{ route('payments.addPayment', [$purchase->id]) }}" 
                                                           class="add_payment_modal">
                                                            <i class="fa fa-money" aria-hidden="true"></i> 
                                                            {{ __("purchase.add_payment") }}
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a href="{{ route('payments.show', [$purchase->id]) }}" 
                                                       class="view_payment_modal">
                                                        <i class="fa fa-money" aria-hidden="true"></i> 
                                                        {{ __("purchase.view_payments") }}
                                                    </a>
                                                </li>
                                            @endcan
                                    
                                            @can("purchase.update")
                                                <li>
                                                    <a href="{{ route('purchase-return.add', [$purchase->id]) }}">
                                                        <i class="fa fa-undo" aria-hidden="true"></i> 
                                                        {{ __("lang_v1.purchase_return") }}
                                                    </a>
                                                </li>
                                            @endcan
                                    
                                            @can("send_notification")
                                                @if($purchase->status == 'ordered')
                                                    <li>
                                                        <a href="#" 
                                                           data-href="{{ route('notification.getTemplate', ['transaction_id' => $purchase->id, 'template_for' => 'new_order']) }}" 
                                                           class="btn-modal" 
                                                           data-container=".view_modal">
                                                            <i class="fa fa-envelope" aria-hidden="true"></i> 
                                                            {{ __("lang_v1.new_order_notification") }}
                                                        </a>
                                                    </li>
                                                @elseif($purchase->status == 'received')
                                                    <li>
                                                        <a href="#" 
                                                           data-href="{{ route('notification.getTemplate', ['transaction_id' => $purchase->id, 'template_for' => 'items_received']) }}" 
                                                           class="btn-modal" 
                                                           data-container=".view_modal">
                                                            <i class="fa fa-envelope" aria-hidden="true"></i> 
                                                            {{ __("lang_v1.item_received_notification") }}
                                                        </a>
                                                    </li>
                                                @elseif($purchase->status == 'pending')
                                                    <li>
                                                        <a href="#" 
                                                           data-href="{{ route('notification.getTemplate', ['transaction_id' => $purchase->id, 'template_for' => 'items_pending']) }}" 
                                                           class="btn-modal" 
                                                           data-container=".view_modal">
                                                            <i class="fa fa-envelope" aria-hidden="true"></i> 
                                                            {{ __("lang_v1.item_pending_notification") }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray font-17 text-center footer-total">
                            <td colspan="4">
                                <strong>{{ __('sale.total') }}:</strong>
                            </td>
                            <td id="footer_status_count">
                                @php
                                    $totals = $this->getFooterTotals();
                                @endphp
                                <small>
                                    {{ __('lang_v1.received') }}: {{ $totals->total_received }}<br>
                                    {{ __('lang_v1.pending') }}: {{ $totals->total_pending }}<br>
                                    {{ __('lang_v1.ordered') }}: {{ $totals->total_ordered }}
                                </small>
                            </td>
                            <td id="footer_payment_status_count">
                                <small>
                                    {{ __('lang_v1.paid') }}: {{ $totals->total_paid }}<br>
                                    {{ __('lang_v1.partial') }}: {{ $totals->total_partial }}<br>
                                    {{ __('lang_v1.due') }}: {{ $totals->total_due }}
                                </small>
                            </td>
                            <td>
                                <span class="display_currency" 
                                      id="footer_purchase_total" 
                                      data-currency_symbol="true">
                                    {{ $totals->total_amount }}
                                </span>
                            </td>
                            <td class="text-left">
                                <small>
                                    {{ __('report.purchase_due') }} - 
                                    <span class="display_currency" 
                                          id="footer_total_due" 
                                          data-currency_symbol="true">
                                        {{ $totals->total_due_amount }}
                                    </span>
                                    <br>
                                    {{ __('lang_v1.purchase_return') }} - 
                                    <span class="display_currency" 
                                          id="footer_total_purchase_return_due" 
                                          data-currency_symbol="true">
                                        {{ $totals->total_return_due_amount }}
                                    </span>
                                </small>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class="col-sm-12">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade product_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade payment_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>

</section>
</div>
