<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1>@lang('lang_v1.purchase_return')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_purchase_returns')])
            @can('purchase.view')
                <div class="row">
                    <div class="col-sm-12">
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
                                <th>@lang('purchase.ref_no')</th>
                                <th>@lang('lang_v1.parent_purchase')</th>
                                <th>@lang('purchase.location')</th>
                                <th>@lang('purchase.supplier')</th>
                                <th>@lang('purchase.payment_status')</th>
                                <th>@lang('purchase.grand_total')</th>
                                <th>@lang('purchase.payment_due') &nbsp;&nbsp;<i class="fa fa-info-circle text-info" data-toggle="tooltip"
                                        data-placement="bottom" data-html="true"
                                        data-original-title="{{ __('messages.purchase_due_tooltip') }}" aria-hidden="true"></i>
                                </th>
                                <th>@lang('messages.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseReturns as $return)
                                <tr>
                                    <td>@format_date($return->transaction_date)</td>
                                    <td>{{ $return->ref_no }}</td>
                                    <td>
                                        <a href="#" 
                                           class="btn-modal" 
                                           data-href="{{ route('purchases.show', [$return->return_parent_id]) }}"
                                           data-container=".view_modal">
                                            {{ $return->parent_purchase }}
                                        </a>
                                    </td>
                                    <td>{{ $return->location_name }}</td>
                                    <td>{{ $return->supplier_name }}</td>
                                    <td>
                                        <span class="label @payment_status($return->payment_status)">
                                            {{ $return->payment_status != 'paid' 
                                                ? __('lang_v1.' . $return->payment_status) 
                                                : __('lang_v1.received') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="display_currency" data-currency_symbol="true">
                                            {{ $return->final_total }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="display_currency" data-currency_symbol="true">
                                            {{ $return->final_total - $return->amount_paid }}
                                        </span>
                                    </td>
                                    <td>
                                        @can('purchase.view')
                                            <a data-href="{{ route('purchase-return.show', [$return->return_parent_id]) }}"
                                               class="btn btn-info btn-xs btn-modal" data-container=".view_modal">
                                                <i class="fa fa-eye"></i> {{ __('View') }}
                                            </a>
                                        @endcan
                                        @can('purchase.update')
                                            <a href="{{ route('purchase-return.add', [$return->return_parent_id]) }}"
                                               class="btn btn-primary btn-xs">
                                                <i class="fa fa-edit"></i> {{ __('Edit') }}
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">{{ __('No records found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray font-17 text-center footer-total">
                                <td colspan="5"><strong>@lang('sale.total'):</strong></td>
                                <td id="footer_payment_status_count"></td>
                                <td><span class="display_currency" id="footer_purchase_return_total"
                                        data-currency_symbol ="true"></span></td>
                                <td><span class="display_currency" id="footer_total_due" data-currency_symbol ="true"></span>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" role="status" aria-live="polite">
                            Showing {{ $purchaseReturns->firstItem() ?? 0 }} to {{ $purchaseReturns->lastItem() ?? 0 }} of {{ $purchaseReturns->total() }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers">
                            <ul class="pagination" style="margin: 2px 0; white-space: nowrap;">
                                {{-- Previous Page Link --}}
                                <li class="paginate_button page-item {{ $purchaseReturns->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" wire:click.prevent="previousPage" href="#" tabindex="-1">Previous</a>
                                </li>
    
                                {{-- Pagination Elements --}}
                                @for ($i = 1; $i <= $purchaseReturns->lastPage(); $i++)
                                    @if ($i == $purchaseReturns->currentPage())
                                        <li class="paginate_button page-item active">
                                            <a class="page-link" href="#">{{ $i }}</a>
                                        </li>
                                    @elseif ($i == 1 || $i == $purchaseReturns->lastPage() || abs($purchaseReturns->currentPage() - $i) <= 2)
                                        <li class="paginate_button page-item">
                                            <a class="page-link" wire:click.prevent="gotoPage({{ $i }})" href="#">{{ $i }}</a>
                                        </li>
                                    @elseif (abs($purchaseReturns->currentPage() - $i) == 3)
                                        <li class="paginate_button page-item disabled">
                                            <a class="page-link" href="#">...</a>
                                        </li>
                                    @endif
                                @endfor
    
                                {{-- Next Page Link --}}
                                <li class="paginate_button page-item {{ !$purchaseReturns->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" wire:click.prevent="nextPage" href="#">Next</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endcan
        @endcomponent

        <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

        <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>

    <!-- /.content -->
</div>
