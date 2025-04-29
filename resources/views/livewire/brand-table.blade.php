<div>
    {{-- Success is as dangerous as failure. --}}
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'brand.brands' )
        <small>@lang( 'brand.manage_your_brands' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'brand.all_your_brands' )])
        @can('brand.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{route('brands.create')}}" 
                        data-container=".brands_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('brand.view')
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
                <table class="table table-bordered table-striped" id="">
                    <thead>
                        <tr>
                            <th>@lang( 'brand.brands' )</th>
                            <th>@lang( 'brand.note' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brands as $brand)
                            <tr>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $brand->description }}</td>
                                <td>
                                    @can('brand.update')
                                        <button data-href="{{ route('brands.edit', [$brand->id]) }}" 
                                                class="btn btn-xs btn-primary edit_brand_button">
                                            <i class="glyphicon glyphicon-edit"></i> 
                                            {{ __("messages.edit") }}
                                        </button>
                                        &nbsp;
                                    @endcan
                                    
                                    @can('brand.delete')
                                        <button data-href="{{ route('brands.destroy', [$brand->id]) }}" 
                                                class="btn btn-xs btn-danger delete_brand_button">
                                            <i class="glyphicon glyphicon-trash"></i> 
                                            {{ __("messages.delete") }}
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" role="status" aria-live="polite">
                        Showing {{ $brands->firstItem() ?? 0 }} to {{ $brands->lastItem() ?? 0 }} of {{ $brands->total() }} entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination" style="margin: 2px 0; white-space: nowrap;">
                            {{-- Previous Page Link --}}
                            <li class="paginate_button page-item {{ $brands->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" wire:click.prevent="previousPage" href="#" tabindex="-1">Previous</a>
                            </li>
    
                            {{-- Pagination Elements --}}
                            @for ($i = 1; $i <= $brands->lastPage(); $i++)
                                @if ($i == $brands->currentPage())
                                    <li class="paginate_button page-item active">
                                        <a class="page-link" href="#">{{ $i }}</a>
                                    </li>
                                @elseif ($i == 1 || $i == $brands->lastPage() || abs($brands->currentPage() - $i) <= 2)
                                    <li class="paginate_button page-item">
                                        <a class="page-link" wire:click.prevent="gotoPage({{ $i }})" href="#">{{ $i }}</a>
                                    </li>
                                @elseif (abs($brands->currentPage() - $i) == 3)
                                    <li class="paginate_button page-item disabled">
                                        <a class="page-link" href="#">...</a>
                                    </li>
                                @endif
                            @endfor
    
                            {{-- Next Page Link --}}
                            <li class="paginate_button page-item {{ !$brands->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" wire:click.prevent="nextPage" href="#">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade brands_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
</div>
