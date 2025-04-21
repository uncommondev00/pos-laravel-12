<div>
    {{-- The whole world belongs to you. --}}
    <!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang('sale.drafts')
    </h1>
</section>

<!-- Main content -->
<section class="content no-print">
    @component('components.widget', ['class' => 'box-primary'])
        @slot('tool')
            <div class="box-tools">
                <a class="btn btn-block btn-primary" href="{{route('pos.create')}}">
                <i class="fa fa-plus"></i> @lang('messages.add')</a>
            </div>
        @endslot
        <div class="form-group">
            <div class="input-group">
              <button type="button" class="btn btn-primary" id="daterange-btn">
                <span>
                  <i class="fa fa-calendar"></i> Filter By Date
                </span>
                <i class="fa fa-caret-down"></i>
              </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="search">@lang('lang_v1.search')</label>
                    <input type="text" class="form-control" id="search" wire:model.debounce.300ms="search" placeholder="Search...">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped ajax_view" id="">
                <thead>
                    <tr>
                        <th>@lang('messages.date')</th>
                        <th>@lang('purchase.ref_no')</th>
                        <th>@lang('sale.customer_name')</th>
                        <th>@lang('sale.location')</th>
                        <th>@lang('messages.action')</th>
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
                                        <a href="#" data-href="{{ route('sells.show', [$sale->id]) }}" class="btn btn-xs btn-success btn-modal" data-container=".view_modal"><i class="fa fa-eye"></i> @lang('messages.view')</a>

                                        @if($sale->is_direct_sale == 1)
                                            <a target="_blank" href="{{route('sells.edit', [$sale->id])}}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>  @lang("messages.edit")</a>
                                        @else
                                        <a target="_blank" href="{{route('pos.edit', [$sale->id])}}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>  @lang("messages.edit")</a>
                                        @endif

                                        &nbsp; 
                                        <a href="#" class="print-invoice btn btn-xs btn-info" data-href="{{route('pos.printInvoice', [$sale->id])}}"><i class="fa fa-print" aria-hidden="true"></i> @lang("messages.print")</a>

                                        <a href="{{ route('sells.destroy', [$sale->id]) }}" class="btn btn-xs btn-danger delete-sale"><i class="fa fa-trash"></i> @lang('messages.delete')</a>
                                    
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">@lang('purchase.no_records_found')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
        </div>
    @endcomponent
</section>
<!-- /.content -->
</div>
