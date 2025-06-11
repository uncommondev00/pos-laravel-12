@extends('layouts.app')
@section('title', __('expense.add_expense'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>@lang('expense.add_expense')</h1>
</section>

<!-- Main content -->
<section class="content">
	<form action="{{ route('expenses.store') }}" method="post" id="add_expense_form" enctype="multipart/form-data">
		@csrf
		<div class="box box-solid">
			<div class="box-body">
				<div class="row">
					@if(count($business_locations) == 1)
					@php
					$default_location = current(array_keys($business_locations->toArray()))
					@endphp
					@else
					@php $default_location = null; @endphp
					@endif
					<div class="col-sm-4">
						<div class="form-group">
							<label for="location_id">{{ __('purchase.business_location') }}:*</label>
							<select name="location_id" id="location_id" class="form-control select2" required>
								<option value="">{{ __('messages.please_select') }}</option>
								@foreach($business_locations as $key => $value)
								<option value="{{ $key }}" {{ $default_location == $key ? 'selected' : '' }}>{{ $value }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-sm-4">
						<div class="form-group">
							<label for="expense_category_id">{{ __('expense.expense_category') }}:</label>
							<select name="expense_category_id" id="expense_category_id" class="form-control select2">
								<option value="">{{ __('messages.please_select') }}</option>
								@foreach($expense_categories as $key => $value)
								<option value="{{ $key }}">{{ $value }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="ref_no">{{ __('purchase.ref_no') }}:</label>
							<input type="text" name="ref_no" id="ref_no" class="form-control">
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="transaction_date">{{ __('messages.date') }}:*</label>
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
								<input type="text" name="transaction_date" id="expense_transaction_date" class="form-control" value="{{ @format_date('now') }}" readonly required>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="final_total">{{ __('sale.total_amount') }}:*</label>
							<input type="text" name="final_total" id="final_total" class="form-control input_number" placeholder="{{ __('sale.total_amount') }}" required>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="expense_for">{{ __('expense.expense_for') }}:</label> @show_tooltip(__('tooltip.expense_for'))
							<select name="expense_for" id="expense_for" class="form-control select2">
								<option value="">{{ __('messages.please_select') }}</option>
								@foreach($users as $key => $value)
								<option value="{{ $key }}">{{ $value }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="document">{{ __('purchase.attach_document') }}:</label>
							<input type="file" name="document" id="upload_document">
							<p class="help-block">@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])</p>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="additional_notes">{{ __('expense.expense_note') }}:</label>
							<textarea name="additional_notes" id="additional_notes" class="form-control" rows="3"></textarea>
						</div>
					</div>
					<div class="col-sm-12">
						<button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
					</div>
				</div>
			</div>
		</div> <!--box end-->
	</form>
</section>
@endsection
