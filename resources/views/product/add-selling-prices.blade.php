@extends('layouts.app')
@section('title', __('lang_v1.add_selling_price_group_prices'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('lang_v1.add_selling_price_group_prices')</h1>
</section>

<!-- Main content -->
<section class="content">
    <form action="{{ route('products.saveSellingPrices', [$product->id]) }}" method="post" id="selling_price_form">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title">@lang('sale.product'): {{ $product->name }} ({{ $product->sku }})</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-bordered table-th-green text-center table-striped">
                                        <thead>
                                            <tr>
                                                @if($product->type == 'variable')
                                                    <th>@lang('lang_v1.variation')</th>
                                                @endif
                                                <th>@lang('lang_v1.default_selling_price_inc_tax')</th>
                                                @foreach($price_groups as $price_group)
                                                    <th>{{ $price_group->name }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->variations as $variation)
                                                <tr>
                                                    @if($product->type == 'variable')
                                                        <td>
                                                            {{ $variation->product_variation->name }} - {{ $variation->name }} ({{ $variation->sub_sku }})
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <span class="display_currency" data-currency_symbol="true">
                                                            {{ $variation->sell_price_inc_tax }}
                                                        </span>
                                                    </td>
                                                    @foreach($price_groups as $price_group)
                                                        <td>
                                                            <input type="text" 
                                                                   name="group_prices[{{ $price_group->id }}][{{ $variation->id }}]" 
                                                                   value="{{ !empty($variation_prices[$variation->id][$price_group->id]) ? @num_format($variation_prices[$variation->id][$price_group->id]) : 0 }}" 
                                                                   class="form-control input_number input-sm">
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <input type="hidden" name="submit_type" value="save" id="submit_type">
                <div class="text-center">
                    <div class="btn-group">
                        <button id="opening_stock_button" 
                                type="submit" 
                                value="submit_n_add_opening_stock" 
                                class="btn bg-purple submit_form"
                                {{ $product->enable_stock == 0 ? 'disabled' : '' }}>
                            @lang('lang_v1.save_n_add_opening_stock')
                        </button>
                        <button type="submit" 
                                value="save_n_add_another" 
                                class="btn bg-maroon submit_form">
                            @lang('lang_v1.save_n_add_another')
                        </button>
                        <button type="submit" 
                                value="submit" 
                                class="btn btn-primary submit_form">
                            @lang('messages.save')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@stop
@section('javascript')
	<script type="text/javascript">
		$(document).ready(function(){
			$('button.submit_form').click( function(e){
				e.preventDefault();
				$('input#submit_type').val($(this).attr('value'));

				if($("form#selling_price_form").valid()) {
		            $("form#selling_price_form").submit();
		        }
			});
		});
	</script>
@endsection
