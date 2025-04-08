@extends('layouts.app')
@section('title', __('product.add_new_product'))

@section('content')

<section class="content-header">
    <h1>@lang('product.add_new_product')</h1>
</section>

<section class="content">
<form action="{{ route('products.store') }}" method="POST" id="product_add_form" class="product_form" enctype="multipart/form-data">
    @csrf
    @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
                <label for="name">{{ __('product.product_name') }}:*</label>
                <input type="text" name="name" class="form-control" required 
                    placeholder="{{ __('product.product_name') }}"
                    value="{{ !empty($duplicate_product->name) ? $duplicate_product->name : null }}">
            </div>
        </div>

            <div class="col-sm-4 @if(!session('business.enable_brand')) hide @endif">
                <div class="form-group">
                    <label for="brand_id">{{ __('product.brand') }}:</label>
                    <div class="input-group">
                        <select name="brand_id" class="form-control select2">
                            <option value="">{{ __('messages.please_select') }}</option>
                            @foreach($brands as $id => $brand)
                                <option value="{{ $id }}" {{ (!empty($duplicate_product->brand_id) && $duplicate_product->brand_id == $id) ? 'selected' : '' }}>
                                    {{ $brand }}
                                </option>
                            @endforeach
                        </select>
                        <span class="input-group-btn">
                            <button type="button" @if(!auth()->user()->can('brand.create')) disabled @endif class="btn btn-default bg-white btn-flat btn-modal" data-href="{{route('brands.create', ['quick_add' => true])}}" title="@lang('brand.add_brand')" data-container=".view_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="unit_id">{{ __('product.unit') }}:*</label>
                    <div class="input-group">
                        <select name="unit_id" class="form-control select2" required>
                            @foreach($units as $id => $unit)
                                <option value="{{ $id }}" {{ (!empty($duplicate_product->unit_id) && $duplicate_product->unit_id == $id) || session('business.default_unit') == $id ? 'selected' : '' }}>
                                    {{ $unit }}
                                </option>
                            @endforeach
                        </select>
                        <span class="input-group-btn">
                            <button type="button" @if(!auth()->user()->can('unit.create')) disabled @endif class="btn btn-default bg-white btn-flat btn-modal" data-href="{{route('units.create', ['quick_add' => true])}}" title="@lang('unit.add_unit')" data-container=".view_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-sm-4 @if(!session('business.enable_category')) hide @endif">
                <div class="form-group">
                    <label for="category_id">{{ __('product.category') }}:</label>
                    <select name="category_id" class="form-control select2">
                        <option value="">{{ __('messages.please_select') }}</option>
                        @foreach($categories as $id => $category)
                            <option value="{{ $id }}" {{ (!empty($duplicate_product->category_id) && $duplicate_product->category_id == $id) ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-4 @if(!(session('business.enable_category') && session('business.enable_sub_category'))) hide @endif">
                <div class="form-group">
                    <label for="sub_category_id">{{ __('product.sub_category') }}:</label>
                    <select name="sub_category_id" class="form-control select2">
                        <option value="">{{ __('messages.please_select') }}</option>
                        @foreach($sub_categories as $id => $sub_category)
                            <option value="{{ $id }}" {{ (!empty($duplicate_product->sub_category_id) && $duplicate_product->sub_category_id == $id) ? 'selected' : '' }}>
                                {{ $sub_category }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="sku">{{ __('product.sku') }}:</label> @show_tooltip(__('tooltip.sku'))
                    <input type="text" name="sku" class="form-control" placeholder="{{ __('product.sku') }}">
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="barcode_type">{{ __('product.barcode_type') }}:*</label>
                    <select name="barcode_type" class="form-control select2" required>
                        @foreach($barcode_types as $key => $value)
                            <option value="{{ $key }}" {{ (!empty($duplicate_product->barcode_type) && $duplicate_product->barcode_type == $key) || $barcode_default == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <br>
                    <label>
                        <input type="checkbox" name="enable_stock" value="1" class="input-icheck" id="enable_stock"
                            {{ !empty($duplicate_product) ? ($duplicate_product->enable_stock ? 'checked' : '') : 'checked' }}>
                        <strong>@lang('product.manage_stock')</strong>
                    </label>
                    @show_tooltip(__('tooltip.enable_stock'))
                    <p class="help-block"><i>@lang('product.enable_stock_help')</i></p>
                </div>
            </div>

            <div class="col-sm-4 {{ !empty($duplicate_product) && $duplicate_product->enable_stock == 0 ? 'hide' : '' }}" id="alert_quantity_div">
                <div class="form-group">
                    <label for="alert_quantity">{{ __('product.alert_quantity') }}:*</label> @show_tooltip(__('tooltip.alert_quantity'))
                    <input type="number" name="alert_quantity" class="form-control" required
                        placeholder="{{ __('product.alert_quantity') }}" min="0"
                        value="{{ !empty($duplicate_product->alert_quantity) ? $duplicate_product->alert_quantity : '' }}">
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-sm-8">
                <div class="form-group">
                    <label for="product_description">{{ __('lang_v1.product_description') }}:</label>
                    <textarea id="product_description" name="product_description" class="form-control">{{ !empty($duplicate_product->product_description) ? $duplicate_product->product_description : '' }}</textarea>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="image">{{ __('lang_v1.product_image') }}:</label>
                    <input type="file" name="image" id="upload_image" accept="image/*">
                    <small>
                        <p class="help-block">
                            @lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                            <br> @lang('lang_v1.aspect_ratio_should_be_1_1')
                        </p>
                    </small>
                </div>
            </div>

        </div>
    @endcomponent


    @component('components.widget', ['class' => 'box-primary hide'])
        <div class="row">
            @if(session('business.enable_product_expiry'))
                @if(session('business.expiry_type') == 'add_expiry')
                    @php
                        $expiry_period = 12;
                        $hide = true;
                    @endphp
                @else
                    @php
                        $expiry_period = null;
                        $hide = false;
                    @endphp
                @endif
                <div class="col-sm-4 @if($hide) hide @endif">
                    <div class="form-group">
                        <div class="multi-input">
                            <label for="expiry_period">{{ __('product.expires_in') }}:</label><br>
                            <input type="text" name="expiry_period" 
                                class="form-control pull-left input_number"
                                style="width:60%;"
                                placeholder="{{ __('product.expiry_period') }}"
                                value="{{ !empty($duplicate_product->expiry_period) ? @num_format($duplicate_product->expiry_period) : $expiry_period }}">
                            <select name="expiry_period_type" class="form-control select2 pull-left" style="width:40%;" id="expiry_period_type">
                                <option value="months" {{ !empty($duplicate_product->expiry_period_type) && $duplicate_product->expiry_period_type == 'months' ? 'selected' : '' }}>
                                    {{ __('product.months') }}
                                </option>
                                <option value="days" {{ !empty($duplicate_product->expiry_period_type) && $duplicate_product->expiry_period_type == 'days' ? 'selected' : '' }}>
                                    {{ __('product.days') }}
                                </option>
                                <option value="" {{ !empty($duplicate_product->expiry_period_type) && $duplicate_product->expiry_period_type == '' ? 'selected' : '' }}>
                                    {{ __('product.not_applicable') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-sm-4">
                <div class="form-group">
                    <br>
                    <label>
                        <input type="checkbox" name="enable_sr_no" value="1" class="input-icheck"
                            {{ !empty($duplicate_product) && $duplicate_product->enable_sr_no ? 'checked' : '' }}>
                        <strong>@lang('lang_v1.enable_imei_or_sr_no')</strong>
                    </label>
                    @show_tooltip(__('lang_v1.tooltip_sr_no'))
                </div>
            </div>

            <div class="clearfix"></div>

            @if(session('business.enable_racks') || session('business.enable_row') || session('business.enable_position'))
                <div class="col-md-12">
                    <h4>@lang('lang_v1.rack_details'):
                        @show_tooltip(__('lang_v1.tooltip_rack_details'))
                    </h4>
                </div>
                @foreach($business_locations as $id => $location)
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="rack_{{ $id }}">{{ $location }}:</label>
                            
                            @if(session('business.enable_racks'))
                                <input type="text" name="product_racks[{{ $id }}][rack]" 
                                    class="form-control" id="rack_{{ $id }}"
                                    placeholder="{{ __('lang_v1.rack') }}"
                                    value="{{ !empty($rack_details[$id]['rack']) ? $rack_details[$id]['rack'] : null }}">
                            @endif

                            @if(session('business.enable_row'))
                                <input type="text" name="product_racks[{{ $id }}][row]"
                                    class="form-control" placeholder="{{ __('lang_v1.row') }}"
                                    value="{{ !empty($rack_details[$id]['row']) ? $rack_details[$id]['row'] : null }}">
                            @endif
                            
                            @if(session('business.enable_position'))
                                <input type="text" name="product_racks[{{ $id }}][position]"
                                    class="form-control" placeholder="{{ __('lang_v1.position') }}"
                                    value="{{ !empty($rack_details[$id]['position']) ? $rack_details[$id]['position'] : null }}">
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
            
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="weight">{{ __('lang_v1.weight') }}:</label>
                    <input type="text" name="weight" class="form-control" 
                        placeholder="{{ __('lang_v1.weight') }}"
                        value="{{ !empty($duplicate_product->weight) ? $duplicate_product->weight : null }}">
                </div>
            </div>

            <div class="clearfix"></div>
            
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="product_custom_field1">{{ __('lang_v1.product_custom_field1') }}:</label>
                    <input type="text" name="product_custom_field1" class="form-control"
                        placeholder="{{ __('lang_v1.product_custom_field1') }}"
                        value="{{ !empty($duplicate_product->product_custom_field1) ? $duplicate_product->product_custom_field1 : null }}">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="product_custom_field2">{{ __('lang_v1.product_custom_field2') }}:</label>
                    <input type="text" name="product_custom_field2" class="form-control"
                        placeholder="{{ __('lang_v1.product_custom_field2') }}"
                        value="{{ !empty($duplicate_product->product_custom_field2) ? $duplicate_product->product_custom_field2 : null }}">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="product_custom_field3">{{ __('lang_v1.product_custom_field3') }}:</label>
                    <input type="text" name="product_custom_field3" class="form-control"
                        placeholder="{{ __('lang_v1.product_custom_field3') }}"
                        value="{{ !empty($duplicate_product->product_custom_field3) ? $duplicate_product->product_custom_field3 : null }}">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="product_custom_field4">{{ __('lang_v1.product_custom_field4') }}:</label>
                    <input type="text" name="product_custom_field4" class="form-control"
                        placeholder="{{ __('lang_v1.product_custom_field4') }}"
                        value="{{ !empty($duplicate_product->product_custom_field4) ? $duplicate_product->product_custom_field4 : null }}">
                </div>
            </div>

            <div class="clearfix"></div>
            @include('layouts.partials.module_form_part')
        </div>
    @endcomponent

    @component('components.widget', ['class' => 'box-primary'])
    <div class="row">
        <div class="col-sm-4 @if(!session('business.enable_price_tax')) hide @endif">
            <div class="form-group">
                <label for="tax">{{ __('product.applicable_tax') }}:</label>
                <select name="tax" class="form-control select2">
                    <option value="">{{ __('messages.please_select') }}</option>
                    @foreach($taxes as $key => $value)
                        <option value="{{ $key }}" 
                            {{ !empty($duplicate_product->tax) && $duplicate_product->tax == $key ? 'selected' : '' }}
                            @if(isset($tax_attributes[$key]))
                                @foreach($tax_attributes[$key] as $k => $v)
                                    {{ $k }}="{{ $v }}"
                                @endforeach
                            @endif
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4 @if(!session('business.enable_price_tax')) hide @endif">
            <div class="form-group">
                <label for="tax_type">{{ __('product.selling_price_tax_type') }}:*</label>
                <select name="tax_type" class="form-control select2" required>
                    <option value="inclusive" {{ !empty($duplicate_product->tax_type) && $duplicate_product->tax_type == 'inclusive' ? 'selected' : '' }}>
                        {{ __('product.inclusive') }}
                    </option>
                    <option value="exclusive" {{ !empty($duplicate_product->tax_type) && $duplicate_product->tax_type == 'exclusive' ? 'selected' : '' }}>
                        {{ __('product.exclusive') }}
                    </option>
                </select>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="type">{{ __('product.product_type') }}:*</label> @show_tooltip(__('tooltip.product_type'))
                <select id="type" name="type" class="form-control select2" required 
                    data-action="{{ !empty($duplicate_product) ? 'duplicate' : 'add' }}"
                    data-product_id="{{ !empty($duplicate_product) ? $duplicate_product->id : '0' }}">
                    <option value="single" {{ !empty($duplicate_product->type) && $duplicate_product->type == 'single' ? 'selected' : '' }}>
                        Single
                    </option>
                    <option value="variable" {{ !empty($duplicate_product->type) && $duplicate_product->type == 'variable' ? 'selected' : '' }}>
                        Variable
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group col-sm-11 col-sm-offset-1" id="product_form_part"></div>

        <input type="hidden" id="variation_counter" value="1">
        <input type="hidden" id="default_profit_percent" value="{{ $default_profit_percent }}">
    </div>
@endcomponent

    <div class="row">
        <div class="col-sm-12">
            <input type="hidden" name="submit_type" id="submit_type">
            <div class="text-center">
                <div class="btn-group">
                    @if($selling_price_group_count)
                        <button type="submit" value="submit_n_add_selling_prices" class="btn btn-warning submit_product_form">@lang('lang_v1.save_n_add_selling_price_group_prices')</button>
                    @endif

                    <button id="opening_stock_button" @if(!empty($duplicate_product) && $duplicate_product->enable_stock == 0) disabled @endif type="submit" value="submit_n_add_opening_stock" class="btn bg-purple submit_product_form">@lang('lang_v1.save_n_add_opening_stock')</button>

                    <button type="submit" value="save_n_add_another" class="btn bg-maroon submit_product_form">@lang('lang_v1.save_n_add_another')</button>

                    <button type="submit" value="submit" class="btn btn-primary submit_product_form">@lang('messages.save')</button>
                </div>
            </div>
        </div>
    </div>
</form>
</section>

@endsection

@section('javascript')
    @php $asset_v = env('APP_VERSION'); @endphp
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
@endsection