<div>
    {{-- Success is as dangerous as failure. --}}

    <!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang('lang_v1.stock_transfers')
    </h1>
</section>

<!-- Main content -->
<section class="content no-print">
    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_stock_transfers')])
        @slot('tool')
            @if(auth()->user()->roles[0]->id == 1 ||   auth()->user()->roles[0]->id == 3 ||   auth()->user()->roles[0]->id == 9)
            <div class="box-tools">
                <a class="btn btn-block btn-primary" href="{{route('stock-transfers.create')}}">
                <i class="fa fa-plus"></i> @lang('messages.add')</a>
            </div>
            @endif
        @endslot
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="search">Search</label>
                    <input type="text" class="form-control" id="search" wire:model.debounce.300ms="search" placeholder="Search...">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="">
                <thead>
                    <tr>
                        <th>@lang('messages.date')</th>
                        <th>@lang('purchase.ref_no')</th>
                        <th>@lang('lang_v1.location_from')</th>
                        <th>@lang('lang_v1.location_to')</th>
                        <th>@lang('lang_v1.shipping_charges')</th>
                        <th>@lang('stock_adjustment.total_amount')</th>
                        <th>@lang('purchase.additional_notes')</th>
                        <th>@lang('messages.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stock_transfers as $transfer)
                        <tr>
                            <td>@format_date($transfer->transaction_date)</td>
                            <td>{{ $transfer->ref_no }}</td>
                            <td>{{ $transfer->location_from }}</td>
                            <td>{{ $transfer->location_to }}</td>
                            <td>
                                <span class="display_currency" data-currency_symbol="true">{{ $transfer->shipping_charges }}</span>
                            </td>
                            <td>
                                <span class="display_currency" data-currency_symbol="true">{{ $transfer->final_total }}</span>
                            </td>
                            <td>
                                {{ $transfer->additional_notes }}
                            </td>
                            <td>
                                <button type="button" title=" __('stock_adjustment.view_details')" class="btn btn-primary btn-xs view_stock_transfer"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                <a href="#" class="print-invoice btn btn-info btn-xs" data-href="{{route('stock-transfers.printInvoice', [$transfer->id])}}"><i class="fa fa-print" aria-hidden="true"></i> @lang('messages.print') </a>
                                <button type="button" data-href="{{route('stock-transfers.destroy', [$transfer->id])}}" class="btn btn-danger btn-xs delete_stock_transfer"><i class="fa fa-trash" aria-hidden="true"></i> @lang('messages.delete') </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">@lang('purchase.no_records_found')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{ $stock_transfers->links() }}
            </div>
        </div>
    @endcomponent
</section>

<section id="receipt_section" class="print_section"></section>

<!-- /.content -->
</div>
