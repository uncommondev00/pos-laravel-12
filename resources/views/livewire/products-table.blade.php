<div>
    {{-- The whole world belongs to you. --}}
    <section class="content-header">
        <h1>@lang('sale.products')
            <small>@lang('lang_v1.manage_products')</small>
        </h1>
    </section>
    
    <section class="content">
        <!-- Filters Component -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('report.filters')</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">{{ __('product.product_type') }}:</label>
                            <select wire:model.live="type" class="form-control " style="width:100%">
                                <option value="">{{ __('lang_v1.all') }}</option>
                                <option value="single">Single</option>
                                <option value="variable">Variable</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category_id">{{ __('product.category') }}:</label>
                            <select wire:model.live="category_id" name="category_id" class="form-control " style="width:100%">
                                <option value="">{{ __('lang_v1.all') }}</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="unit_id">{{ __('product.unit') }}:</label>
                            <select wire:model.live="unit_id" name="unit_id" class="form-control " style="width:100%">
                                <option value="">{{ __('lang_v1.all') }}</option>
                                @foreach($units as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tax_id">{{ __('product.tax') }}:</label>
                            <select wire:model.live="tax_id" name="tax_id"  class="form-control " style="width:100%">
                                <option value="">{{ __('lang_v1.all') }}</option>
                                @foreach($taxes as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="brand_id">{{ __('product.brand') }}:</label>
                            <select wire:model.live="brand_id" name="brand_id"  class="form-control " style="width:100%">
                                <option value="">{{ __('lang_v1.all') }}</option>
                                @foreach($brands as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Products Table Component -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('lang_v1.all_products')</h3>
                @can('product.create')
                    <div class="box-tools">
                        <a class="btn btn-block btn-primary" href="{{ route('products.create') }}">
                            <i class="fa fa-plus"></i> @lang('messages.add')</a>
                    </div>
                @endcan
            </div>
    
            <div class="box-body">
                @can('product.view')
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
                        <table class="table table-bordered table-striped ajax_view table-text-center" id="product_table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all-row"></th>
                                    <th>&nbsp;</th>
                                    <th>@lang('sale.product')</th>
                                    <th>@lang('product.product_type')</th>
                                    <th>@lang('product.category')</th>
                                    <th>@lang('product.sub_category')</th>
                                    <th>@lang('product.unit')</th>
                                    <th>@lang('product.brand')</th>
                                    <th>@lang('product.tax')</th>
                                    <th>@lang('product.sku')</th>
                                    <th>@lang('Stocks')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td><input type="checkbox" class="row-select" value="{{ $product->id }}"></td>
                                        <td>
                                            @if($product->image)
                                                <img src="{{ asset('/storage/img/' . $product->image) }}" alt="Product Image" class="product-thumbnail">
                                            @endif
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->type }}</td>
                                        <td>{{ optional($product->category)->name }}</td>
                                        <td>{{ optional($product->sub_category)->name }}</td>
                                        <td>{{ optional($product->unit)->actual_name }}</td>
                                        <td>{{ optional($product->brand)->name }}</td>
                                        <td>{{ optional($product->product_tax)->name }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                                            @if($product->enable_stock)
                                                {{ number_format($product->total_stock, 2) }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                                                        data-toggle="dropdown" 
                                                        aria-expanded="false">
                                                    {{ __("messages.actions") }}
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    <!-- Barcode/Label -->
                                                    <li>
                                                        <a href="{{ route('labels.show') }}?product_id={{ $product->id }}" 
                                                           data-toggle="tooltip" 
                                                           title="Print Barcode/Label">
                                                            <i class="fa fa-barcode"></i> {{ __('barcode.labels') }}
                                                        </a>
                                                    </li>
                                            
                                                    <!-- View Product -->
                                                    @can('product.view')
                                                        <li>
                                                            <a href="{{ route('products.view', [$product->id]) }}" 
                                                               class="view-product">
                                                                <i class="fa fa-eye"></i> {{ __("messages.view") }}
                                                            </a>
                                                        </li>
                                                    @endcan
                                            
                                                    <!-- Edit Product -->
                                                    @can('product.update')
                                                        <li>
                                                            <a href="{{ route('products.edit', [$product->id]) }}">
                                                                <i class="glyphicon glyphicon-edit"></i> {{ __("messages.edit") }}
                                                            </a>
                                                        </li>
                                                    @endcan
                                            
                                                    <!-- Delete Product -->
                                                    @can('product.delete')
                                                        <li>
                                                            <a href="{{ route('products.destroy', [$product->id]) }}" 
                                                               class="delete-product">
                                                                <i class="fa fa-trash"></i> {{ __("messages.delete") }}
                                                            </a>
                                                        </li>
                                                    @endcan
                                            
                                                    <!-- Reactivate Product -->
                                                    @if($product->is_inactive == 1)
                                                        <li>
                                                            <a href="{{ route('products.activate', [$product->id]) }}" 
                                                               class="activate-product">
                                                                <i class="fa fa-circle-o"></i> {{ __("lang_v1.reactivate") }}
                                                            </a>
                                                        </li>
                                                    @endif
                                            
                                                    <li class="divider"></li>
                                            
                                                    @can('product.create')
                                                        <!-- Opening Stock -->
                                                        @if(auth()->user()->can('product.opening_stock') && $product->enable_stock == 1)
                                                            <li>
                                                                <a href="#" 
                                                                   data-href="{{ route('opening-stocks.add', ['product_id' => $product->id]) }}" 
                                                                   class="add-opening-stock">
                                                                    <i class="fa fa-database"></i> {{ __("lang_v1.add_edit_opening_stock") }}
                                                                </a>
                                                            </li>
                                                        @endif
                                            
                                                        <!-- Selling Price Group -->
                                                        @if($selling_price_group_count > 0)
                                                            <li>
                                                                <a href="{{ route('products.addSellingPrices', [$product->id]) }}">
                                                                    <i class="fa fa-money"></i> {{ __("lang_v1.add_selling_price_group_prices") }}
                                                                </a>
                                                            </li>
                                                        @endif
                                            
                                                        <!-- Duplicate Product -->
                                                        <li>
                                                            <a href="{{ route('products.create', ["d" => $product->id]) }}">
                                                                <i class="fa fa-copy"></i> {{ __("lang_v1.duplicate_product") }}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="11">
                                        <div style="display: flex; width: 100%;">
                                            @can('product.delete')
                                                <form action="{{ route('products.massDestroy') }}" method="POST" id="mass_delete_form">
                                                    @csrf
                                                    <input type="hidden" name="selected_rows" id="selected_rows">
                                                    <button type="submit" class="btn btn-xs btn-danger" id="delete-selected">
                                                        {{ __('lang_v1.delete_selected') }}
                                                    </button>
                                                </form>
                                            @endcan
                                            &nbsp;
                                            <form action="{{ route('products.massDeactivate') }}" method="POST" id="mass_deactivate_form">
                                                @csrf
                                                <input type="hidden" name="selected_products" id="selected_products">
                                                <button type="submit" class="btn btn-xs btn-warning" id="deactivate-selected">
                                                    {{ __('lang_v1.deactivate_selected') }}
                                                </button>
                                            </form>
                                            @show_tooltip(__('lang_v1.deactive_product_tooltip'))
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" role="status" aria-live="polite">
                                Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers">
                                <ul class="pagination" style="margin: 2px 0; white-space: nowrap;">
                                    {{-- Previous Page Link --}}
                                    <li class="paginate_button page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" wire:click.prevent="previousPage" href="#" tabindex="-1">Previous</a>
                                    </li>
    
                                    {{-- Pagination Elements --}}
                                    @for ($i = 1; $i <= $products->lastPage(); $i++)
                                        @if ($i == $products->currentPage())
                                            <li class="paginate_button page-item active">
                                                <a class="page-link" href="#">{{ $i }}</a>
                                            </li>
                                        @elseif ($i == 1 || $i == $products->lastPage() || abs($products->currentPage() - $i) <= 2)
                                            <li class="paginate_button page-item">
                                                <a class="page-link" wire:click.prevent="gotoPage({{ $i }})" href="#">{{ $i }}</a>
                                            </li>
                                        @elseif (abs($products->currentPage() - $i) == 3)
                                            <li class="paginate_button page-item disabled">
                                                <a class="page-link" href="#">...</a>
                                            </li>
                                        @endif
                                    @endfor
    
                                    {{-- Next Page Link --}}
                                    <li class="paginate_button page-item {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                                        <a class="page-link" wire:click.prevent="nextPage" href="#">Next</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    
        <input type="hidden" id="is_rack_enabled" value="{{ $rack_enabled }}">
    
        <!-- Modals -->
        <div class="modal fade product_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
        <div class="modal fade" id="view_product_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
        <div class="modal fade" id="opening_stock_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
    </section>
    
</div>
