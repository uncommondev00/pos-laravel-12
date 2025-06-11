@extends('layouts.app')
@section('title', __('expense.edit_expense'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>@lang('expense.edit_expense')</h1>
</section>

<!-- Main content -->
<section class="content">
  <form action="{{ route('expenses.update', $expense->id) }}" method="POST" id="add_expense_form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="box box-solid">
      <div class="box-body">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="location_id">{{ __('purchase.business_location') }}:*</label>
              <select name="location_id" id="location_id" class="form-control select2" required>
                <option value="">{{ __('messages.please_select') }}</option>
                @foreach($business_locations as $key => $value)
                <option value="{{ $key }}" {{ $expense->location_id == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                <option value="{{ $key }}" {{ $expense->expense_category_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="ref_no">{{ __('purchase.ref_no') }}:*</label>
              <input type="text" name="ref_no" id="ref_no" class="form-control" value="{{ $expense->ref_no }}" required>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="transaction_date">{{ __('messages.date') }}:*</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </span>
                <input type="text" name="transaction_date" id="expense_transaction_date" class="form-control" value="{{ date('m/d/Y', strtotime($expense->transaction_date)) }}" readonly required>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="final_total">{{ __('sale.total_amount') }}:*</label>
              <input type="text" name="final_total" id="final_total" class="form-control input_number" value="{{ @num_format($expense->final_total) }}" placeholder="{{ __('sale.total_amount') }}" required>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="expense_for">{{ __('expense.expense_for') }}:</label> @show_tooltip(__('tooltip.expense_for'))
              <select name="expense_for" id="expense_for" class="form-control select2">
                <option value="">{{ __('messages.please_select') }}</option>
                @foreach($users as $key => $value)
                <option value="{{ $key }}" {{ $expense->expense_for == $key ? 'selected' : '' }}>{{ $value }}</option>
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
              <textarea name="additional_notes" id="additional_notes" class="form-control" rows="3">{{ $expense->additional_notes }}</textarea>
            </div>
          </div>
          <div class="col-sm-12">
            <button type="submit" class="btn btn-primary pull-right">@lang('messages.update')</button>
          </div>
        </div>
      </div>
    </div> <!--box end-->
  </form>
</section>
@endsection
