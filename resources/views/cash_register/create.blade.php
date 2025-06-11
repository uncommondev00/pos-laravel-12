@extends('layouts.app')
@section('title', __('cash_register.open_cash_register'))

@section('content')
<style type="text/css">



</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>@lang('cash_register.open_cash_register')</h1>
  <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
  <form action="{{ route('cash-register.store') }}" method="post" id="add_cash_register_form">
    <div class="box box-solid">
      <div class="box-body">
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <div class="form-group">
              <label for="amount">{{ __('cash_register.cash_in_hand') }}:</label>
              <input type="text" name="amount" id="amount" class="form-control input_number" placeholder="{{ __('cash_register.enter_amount') }}">
            </div>
          </div>
          <div class="col-sm-8 col-sm-offset-2">
            <button type="submit" class="btn btn-primary pull-right">@lang('cash_register.open_register')</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</section>
<!-- /.content -->
@endsection
