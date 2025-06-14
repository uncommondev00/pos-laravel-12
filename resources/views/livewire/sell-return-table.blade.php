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
            <div class="row mb-3">
                <div class="col-sm-12 col-md-6">
                    <div class="dataTables_length">
                        <label>
                            Show 
                            <select wire:model.live="perPage" class="form-control form-control-sm" style="width: auto; display: inline-block;">
                                @foreach($perPageOptions as $option)
                                    @if($option === -1)
                                        <option value="{{ $option }}">All</option>
                                    @else
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endif
                                @endforeach
                            </select>
                            entries
                        </label>
                    </div>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="dataTables_filter">
                        <label>
                            Search:
                            <div class="input-group" style="display: inline-flex; width: auto;">
                                <input type="search" 
                                    wire:model.live.debounce.500ms="search" 
                                    class="form-control form-control-sm" 
                                    placeholder="Type to search..."
                                    style="width: 200px;">
                                @if($search)
                                    <div class="input-group-append">
                                        <button wire:click="$set('search', '')" class="btn btn-sm btn-default">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </label>
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
                
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" role="status" aria-live="polite">
                        Showing {{ $sells->firstItem() ?? 0 }} to {{ $sells->lastItem() ?? 0 }} of {{ $sells->total() }} entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination" style="margin: 2px 0; white-space: nowrap;">
                            {{-- Previous Page Link --}}
                            <li class="paginate_button page-item {{ $sells->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" wire:click.prevent="previousPage" href="#" tabindex="-1">Previous</a>
                            </li>

                            {{-- Pagination Elements --}}
                            @for ($i = 1; $i <= $sells->lastPage(); $i++)
                                @if ($i == $sells->currentPage())
                                    <li class="paginate_button page-item active">
                                        <a class="page-link" href="#">{{ $i }}</a>
                                    </li>
                                @elseif ($i == 1 || $i == $sells->lastPage() || abs($sells->currentPage() - $i) <= 2)
                                    <li class="paginate_button page-item">
                                        <a class="page-link" wire:click.prevent="gotoPage({{ $i }})" href="#">{{ $i }}</a>
                                    </li>
                                @elseif (abs($sells->currentPage() - $i) == 3)
                                    <li class="paginate_button page-item disabled">
                                        <a class="page-link" href="#">...</a>
                                    </li>
                                @endif
                            @endfor

                            {{-- Next Page Link --}}
                            <li class="paginate_button page-item {{ !$sells->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" wire:click.prevent="nextPage" href="#">Next</a>
                            </li>
                        </ul>
                    </div>
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
