@extends('layouts.app')
@section('title', __('stock_adjustment.add'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
<br>
    <h1>@lang('stock_adjustment.add')</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content no-print">
	<form action="{{ route('stock-adjustments.store') }}" method="post" id="stock_adjustment_form">
		@csrf
		<div class="box box-solid">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="location_id">{{ __('purchase.business_location') }}:*</label>
							<select name="location_id" id="location_id" class="form-control select2" required>
								<option value="">{{ __('messages.please_select') }}</option>
								@foreach($business_locations as $key => $value)
									<option value="{{ $key }}">{{ $value }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="ref_no">{{ __('purchase.ref_no') }}:</label>
							<input type="text" name="ref_no" id="ref_no" class="form-control">
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="transaction_date">{{ __('messages.date') }}:*</label>
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
								<input type="text" name="transaction_date" id="transaction_date" class="form-control" value="@format_date('now')" readonly required>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="adjustment_type">{{ __('stock_adjustment.adjustment_type') }}:*</label> @show_tooltip(__('tooltip.adjustment_type'))
							<select name="adjustment_type" id="adjustment_type" class="form-control select2" required>
								<option value="">{{ __('messages.please_select') }}</option>
								<option value="normal">{{ __('stock_adjustment.normal') }}</option>
								<option value="abnormal">{{ __('stock_adjustment.abnormal') }}</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div> <!--box end-->
		<div class="box box-solid">
			<div class="box-header">
				<h3 class="box-title">{{ __('stock_adjustment.search_products') }}</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-search"></i>
								</span>
								<input type="text" name="search_product" id="search_product_for_srock_adjustment" class="form-control" placeholder="{{ __('stock_adjustment.search_product') }}" disabled>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<input type="hidden" id="product_row_index" value="0">
						<input type="hidden" id="total_amount" name="final_total" value="0">
						<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed" 
						id="stock_adjustment_product_table">
							<thead>
								<tr>
									<th class="col-sm-4 text-center">	
										@lang('sale.product')
									</th>
									<th class="col-sm-2 text-center">
										@lang('sale.qty')
									</th>
									<th class="col-sm-2 text-center">
										@lang('sale.unit_price')
									</th>
									<th class="col-sm-2 text-center">
										@lang('sale.subtotal')
									</th>
									<th class="col-sm-2 text-center"><i class="fa fa-trash" aria-hidden="true"></i></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
								<tr class="text-center"><td colspan="3"></td><td><div class="pull-right"><b>@lang('stock_adjustment.total_amount'):</b> <span id="total_adjustment">0.00</span></div></td></tr>
							</tfoot>
						</table>
						</div>
					</div>
				</div>
			</div>
		</div> <!--box end-->
		<div class="box box-solid">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
								<label for="total_amount_recovered">{{ __('stock_adjustment.total_amount_recovered') }}:</label> @show_tooltip(__('tooltip.total_amount_recovered'))
								<input type="text" name="total_amount_recovered" id="total_amount_recovered" class="form-control input_number" placeholder="{{ __('stock_adjustment.total_amount_recovered') }}" value="0">
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
								<label for="additional_notes">{{ __('stock_adjustment.reason_for_stock_adjustment') }}:</label>
								<textarea name="additional_notes" id="additional_notes" class="form-control" placeholder="{{ __('stock_adjustment.reason_for_stock_adjustment') }}" rows="3"></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
					</div>
				</div>

			</div>
		</div> <!--box end-->
	</form>
</section>
@stop
@section('javascript')
	<script src="{{ asset('js/stock_adjustment.js?v=' . $asset_v) }}"></script>
@endsection
