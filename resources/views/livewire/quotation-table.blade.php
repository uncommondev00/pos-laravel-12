<div>
    {{-- Success is as dangerous as failure. --}}
    <!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang('lang_v1.list_quotations')
        <small></small>
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
        <div class="table-responsive">
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
    @endcomponent
</section>
<!-- /.content -->
</div>
