@extends('layouts.app')
@section('title', __('lang_v1.add_opening_stock'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>@lang('lang_v1.add_opening_stock')</h1>
</section>

<!-- Main content -->
<section class="content">
	<form action="{{ route('opening-stocks.save') }}" method="POST" id="add_opening_stock_form">
		@csrf
		<input type="hidden" name="product_id" value="{{ $product->id }}">
		@include('opening_stock.form-part')
		<div class="row">
			<div class="col-sm-12">
				<button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
			</div>
		</div>
	</form>
</section>
@stop
@section('javascript')
<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>

@endsection
