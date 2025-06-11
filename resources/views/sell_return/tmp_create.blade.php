@extends('layouts.app')
@section('title', __('lang_v1.sell_return'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
	<h1>@lang('lang_v1.sell_return')</h1>
	<!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content no-print">

	@include('layouts.partials.error')

	@if(count($business_locations) == 1)
	@php
	$default_location = current(array_keys($business_locations->toArray()))
	@endphp
	@else
	@php $default_location = null; @endphp
	@endif
	<div class="row">
		<div class="col-sm-3">
			<div class="form-group">
				{!! Form::label('location_id', __('purchase.business_location').':*') !!}
				{!! Form::select('location_id', $business_locations, $default_location, ['class' => 'form-control', 'placeholder' => __('messages.please_select'), 'required',
				'id' => 'select_location_id']); !!}
			</div>
		</div>
	</div>
	<input type="hidden" id="product_row_count" value="0">

	<form action="{{ route('sell-return.store') }}" method="POST" id="sell_return_form">
		@csrf

		<div class="box box-solid">
			<div class="box-body">
				<div class="row">
					<input type="hidden" name="location_id" value="{{ $default_location }}" id="location_id">

					<div class="col-sm-3">
						<div class="form-group">
							<label for="contact_id">{{ __('contact.customer') }}:*</label>
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-user"></i>
								</span>
								<select name="contact_id" class="form-control" id="customer_id" placeholder="Enter Customer name / phone" required>
									<option value="">Enter Customer name / phone</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="invoice_no">{{ __('purchase.ref_no') }}:</label>
							<input type="text" name="invoice_no" id="invoice_no" class="form-control">
						</div>
					</div>

					<div class="col-sm-3">
						<div class="form-group">
							<label for="transaction_date">{{ __('purchase.purchase_date') }}:*</label>
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
								<input type="text" name="transaction_date" id="transaction_date" class="form-control" value="{{ @format_date('now') }}" readonly required>
							</div>
						</div>
					</div>


				</div>
			</div>
		</div> <!--box end-->

		<div class="box box-solid"><!--box start-->
			<div class="box-body">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-search"></i>
								</span>
								<input type="text"
									name="search_product"
									class="form-control"
									id="search_product"
									placeholder="{{ __('lang_v1.search_product_placeholder') }}"
									{{ is_null($default_location) ? 'disabled' : '' }}
									{{ is_null($default_location) ? '' : 'autofocus' }}>
							</div>
						</div>
					</div>
				</div>
				@php
				$hide_tax = '';
				if( session()->get('business.enable_inline_tax') == 0){
				$hide_tax = 'hide';
				}
				@endphp

				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-condensed table-bordered table-th-green text-center table-striped" id="purchase_entry_table">
								<thead>
									<tr>
										<th class="text-center">
											@lang('sale.product')
										</th>
										<th class="text-center">
											@lang('sale.qty')
										</th>
										<th class="text-center">
											@lang('sale.unit_price')
										</th>
										<th class="text-center {{$hide_tax}}">
											@lang('sale.tax')
										</th>
										<th class="text-center {{$hide_tax}}">
											@lang('sale.price_inc_tax')
										</th>
										<th class="text-center">
											@lang('sale.subtotal')
										</th>

										@if(session('business.enable_lot_number'))
										<th class="text-center">
											@lang('lang_v1.lot_number')
										</th>
										@endif

										@if(session('business.enable_product_expiry'))
										<th class="text-center">
											@lang('product.exp_date')
										</th>
										@endif

										<th class="text-center"><i class="fa fa-trash" aria-hidden="true"></i></th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<hr />
						<div class="pull-right col-md-5">
							<table class="pull-right col-md-12">
								<tr class="hide">
									<th class="col-md-7 text-right">@lang( 'purchase.total_before_tax' ):</th>
									<td class="col-md-5 text-left">
										<span id="total_st_before_tax" class="display_currency"></span>
										<input type="hidden" id="st_before_tax_input" value=0>
									</td>
								</tr>
								<tr>
									<th class="col-md-7 text-right">@lang( 'purchase.net_total_amount' ):</th>
									<td class="col-md-5 text-left">
										<span id="price_total" class="display_currency"></span>
										<!-- This is total before purchase tax-->
										<input type="hidden" id="total_subtotal_input" value=0 name="total_before_tax">
									</td>
								</tr>
							</table>
						</div>

						<input type="hidden" id="row_count" value="0">
					</div>
				</div>
			</div>
		</div><!--box end-->
		<div class="box box-solid"><!--box start-->
			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<table class="table">
							<tr>
								<td class="col-md-3">
									<div class="form-group">
										<label for="discount_type">{{ __('purchase.discount_type') }}:</label>
										<select name="discount_type" id="discount_type" class="form-control">
											<option value="">{{ __('lang_v1.none') }}</option>
											<option value="fixed">{{ __('lang_v1.fixed') }}</option>
											<option value="percentage">{{ __('lang_v1.percentage') }}</option>
										</select>
									</div>
								</td>
								<td class="col-md-3">
									<div class="form-group">
										<label for="discount_amount">{{ __('purchase.discount_amount') }}:</label>
										<input type="text" name="discount_amount" id="discount_amount" class="form-control input_number" value="0" required>
									</div>
								</td>
								<td class="col-md-3">
									&nbsp;
								</td>
								<td class="col-md-3">
									<b>@lang( 'purchase.discount' ):</b>(-)
									<span id="total_discount" class="display_currency">0</span>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>
									<input type="hidden" name="final_total" id="final_total_input" value="0">
									<b>@lang('lang_v1.total_credit_amt'): </b><span id="total_payable" class="display_currency" data-currency_symbol='true'>0</span>
								</td>
							</tr>
							<tr>
								<td colspan="4">
									<div class="form-group">
										<label for="additional_notes">{{ __('purchase.additional_notes') }}</label>
										<textarea name="additional_notes" id="additional_notes" class="form-control" rows="3"></textarea>
									</div>
								</td>
							</tr>

						</table>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<button type="button" id="submit_sell_return_form" class="btn btn-primary pull-right btn-flat">@lang('messages.save')</button>
					</div>
				</div>

			</div><!--box end-->
	</form>
</section>
<!-- /.content -->
@endsection

@section('javascript')
<script src="{{ asset('js/sell_return.js?v=' . $asset_v) }}"></script>
@endsection
