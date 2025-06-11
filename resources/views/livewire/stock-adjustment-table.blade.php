<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('stock_adjustment.stock_adjustments')
            <small></small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('stock_adjustment.all_stock_adjustments')])
        @slot('tool')
        @if(auth()->user()->roles[0]->id == 1 || auth()->user()->roles[0]->id == 3)
        <div class="box-tools">
            <a class="btn btn-block btn-primary" href="{{route('stock-adjustments.create')}}">
                <i class="fa fa-plus"></i> @lang('messages.add')</a>
        </div>
        @endif
        @endslot
        <!-- <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="search">Search</label>
                    <input type="text" class="form-control" id="search" wire:model.debounce.300ms="search" placeholder="Search...">
                </div>
            </div>
        </div> -->
        <div class="table-responsive">
            @include('includes.table-controls', [
            'perPageOptions' => $perPageOptions,
            'search' => $search,
            ])
            <table class="table table-bordered table-striped" id="">
                <thead>
                    <tr>
                        <th>@lang('messages.date')</th>
                        <th>@lang('purchase.ref_no')</th>
                        <th>@lang('business.location')</th>
                        <th>@lang('stock_adjustment.adjustment_type')</th>
                        <th>@lang('stock_adjustment.total_amount')</th>
                        <th>@lang('stock_adjustment.total_amount_recovered')</th>
                        <th>@lang('stock_adjustment.reason_for_stock_adjustment')</th>
                        <th>@lang('messages.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stock_adjustments as $adjustment)
                    <tr>
                        <td>@format_date($adjustment->transaction_date)</td>
                        <td>{{ $adjustment->ref_no }}</td>
                        <td>{{ $adjustment->location_name }}</td>
                        <td>@lang('stock_adjustment.' . $adjustment->adjustment_type)</td>
                        <td>
                            <span class="display_currency" data-currency_symbol="true">{{ $adjustment->final_total }}</span>
                        </td>
                        <td>
                            <span class="display_currency" data-currency_symbol="true">{{ $adjustment->total_amount_recovered }}</span>
                        </td>
                        <td>{{ $adjustment->additional_notes }}</td>
                        <td>
                            <button type="button" title="{{ __('stock_adjustment.view_details') }}" class="btn btn-primary btn-xs view_stock_adjustment"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                            @if(auth()->user()->roles[0]->id == 1 || auth()->user()->roles[0]->id == 3)
                            <button type="button" data-href="{{ route('stock-adjustments.destroy', [$adjustment->id]) }}" class="btn btn-danger btn-xs delete_stock_adjustment {{ $hide }}"><i class="fa fa-trash" aria-hidden="true"></i> @lang('messages.delete')</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">@lang('purchase.no_records_found')</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-sm-12">
                @include('includes.pagination', ['paginator' => $stock_adjustments])
            </div>
        </div>
        @endcomponent

    </section>
    <!-- /.content -->
</div>
