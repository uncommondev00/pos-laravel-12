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
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Filters') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" 
                                       wire:model.debounce.300ms="search" 
                                       class="form-control" 
                                       placeholder="{{ __('Search Reference or Supplier...') }}">
                            </div>
                            <div class="col-md-2">
                                <select wire:model="perPage" class="form-control">
                                    <option value="10">10 {{ __('per page') }}</option>
                                    <option value="25">25 {{ __('per page') }}</option>
                                    <option value="50">50 {{ __('per page') }}</option>
                                    <option value="100">100 {{ __('per page') }}</option>
                                </select>
                            </div>
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
                                    <td>{{ @format_date($return->transaction_date) }}</td>
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
                                                <i class="fas fa-eye"></i> {{ __('View') }}
                                            </a>
                                        @endcan
                                        @can('purchase.update')
                                            <a href="{{ route('purchase-return.add', [$return->return_parent_id]) }}"
                                               class="btn btn-primary btn-xs">
                                                <i class="fas fa-edit"></i> {{ __('Edit') }}
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

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div>
                            {{ __('Showing') }} 
                            {{ $purchaseReturns->firstItem() ?? 0 }} 
                            {{ __('to') }} 
                            {{ $purchaseReturns->lastItem() ?? 0 }} 
                            {{ __('of') }} 
                            {{ $purchaseReturns->total() }} 
                            {{ __('entries') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{ $purchaseReturns->links() }}
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
