<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang('lang_v1.sell_return')
    </h1>
</section>

<!-- Main content -->
<section class="content no-print">
    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.sell_return')])
        @can('sell.view')
            <div class="form-group">
                <div class="input-group">
                  <button type="button" class="btn btn-primary" id="daterange-btn">
                    <span>
                      <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                    </span>
                    <i class="fa fa-caret-down"></i>
                  </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search">Search</label>
                        <input type="text" wire:model.live="search" class="form-control" placeholder="Search invoice or customer">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="location">Location</label>
                        <select wire:model.live="location" class="form-control">
                            <option value="">All Locations</option>
                            @foreach($locations as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="customer">Customer</label>
                        <select wire:model.live="customer" class="form-control">
                            <option value="">All Customers</option>
                            @foreach($customers as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fromDate">From Date</label>
                        <input type="date" wire:model.live="fromDate" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="toDate">To Date</label>
                        <input type="date" wire:model.live="toDate" class="form-control">
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped ajax_view" id="">
                    <thead>
                        <tr>
                            <th>@lang('messages.date')</th>
                            <th>@lang('sale.invoice_no')</th>
                            <th>@lang('lang_v1.parent_sale')</th>
                            <th>@lang('sale.customer_name')</th>
                            <th>@lang('sale.location')</th>
                            <th>@lang('purchase.payment_status')</th>
                            <th>@lang('sale.total_amount')</th>
                            <th>@lang('purchase.payment_due')</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sells as $sell)
                            <tr>
                                <td>@format_date($sell->transaction_date)</td>
                                <td>{{ $sell->invoice_no }}</td>
                                <td>{{ $sell->customer_name }}</td>
                                <td>{{ $sell->business_location }}</td>
                                <td>
                                    @include('components.sell-return.buttons.modal', [
                                        'value' => $sell->parent_sale,
                                        'row' => $sell,
                                        'route' => route('sells.show', $sell->parent_sale_id)
                                    ])
                                </td>
                                <td>
                                    @include('components.sell-return.badges.payment-status', [
                                        'status' => $sell->payment_status,
                                        'id' => $sell->id
                                    ])
                                </td>
                                <td>
                                    <span class="display_currency" data-currency_symbol="true">
                                        {{ $sell->final_total }}
                                    </span>
                                </td>
                                <td>
                                    <span class="display_currency" data-currency_symbol="true">
                                        {{ $sell->final_total - $sell->amount_paid }}
                                    </span>
                                </td>
                                <td>
                                    @include('components.sell-return.actions.sell-return', [
                                        'row' => $sell
                                    ])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray font-17 text-center footer-total">
                            <td colspan="5"><strong>@lang('sale.total'):</strong></td>
                            <td id="footer_payment_status_count"></td>
                            <td><span class="display_currency" id="footer_sell_return_total" data-currency_symbol ="true"></span></td>
                            <td><span class="display_currency" id="footer_total_due" data-currency_symbol ="true"></span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="mt-3">
                    {{ $sells->links() }}
                </div>
            </div>
        @endcan
    @endcomponent
    <div class="modal fade payment_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>
</section>

<!-- /.content -->
</div>
