@extends('layouts.app')
@section('title', __('lang_v1.add_stock_transfer'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>@lang('lang_v1.add_stock_transfer')</h1>
</section>

<!-- Main content -->
<section class="content no-print">
	<form action="{{ route('stock-transfers.store') }}" method="post" id="stock_transfer_form">
		@csrf
		<div class="box box-solid">
			<div class="box-body">
				<div class="row">
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
							<label for="ref_no">{{ __('purchase.ref_no') }}:</label>
							<input type="text" name="ref_no" id="ref_no" class="form-control">
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="location_id">{{ __('lang_v1.location_from') }}:*</label>
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
							<label for="transfer_location_id">{{ __('lang_v1.location_to') }}:*</label>
							<select name="transfer_location_id" id="transfer_location_id" class="form-control select2" required>
								<option value="">{{ __('messages.please_select') }}</option>
								@foreach($business_locations as $key => $value)
								<option value="{{ $key }}">{{ $value }}</option>
								@endforeach
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
									<tr class="text-center">
										<td colspan="3"></td>
										<td>
											<div class="pull-right"><b>@lang('stock_adjustment.total_amount'):</b> <span id="total_adjustment">0.00</span></div>
										</td>
									</tr>
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
							<label for="shipping_charges">{{ __('lang_v1.shipping_charges') }}:</label>
							<input type="text" name="shipping_charges" id="shipping_charges" class="form-control input_number" placeholder="{{ __('lang_v1.shipping_charges') }}" value="0">
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="additional_notes">{{ __('purchase.additional_notes') }}</label>
							<textarea name="additional_notes" id="additional_notes" class="form-control" rows="3"></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<button type="submit" id="save_stock_transfer" class="btn btn-primary pull-right">@lang('messages.save')</button>
					</div>
				</div>

			</div>
		</div> <!--box end-->
	</form>
</section>
@stop
@section('javascript')
<script src="{{ asset('js/stock_transfer.js?v=' . $asset_v) }}"></script>
@endsection
