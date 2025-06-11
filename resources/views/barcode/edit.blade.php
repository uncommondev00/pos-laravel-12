@extends('layouts.app')
@section('title', __('barcode.edit_barcode_setting'))

@section('content')
<style type="text/css">



</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>@lang('barcode.edit_barcode_setting')</h1>
</section>

<!-- Main content -->
<section class="content">
  <form action="{{ route('barcode.update', [$barcode->id]) }}" method="POST" id="add_barcode_settings_form">
    @csrf
    @method('PUT')
    <div class="box box-solid">
      <div class="box-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="name">{{ __('barcode.setting_name') }}:*</label>
              <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __('barcode.setting_name') }}" value="{{ $barcode->name }}">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label for="description">{{ __('barcode.setting_description') }}</label>
              <textarea name="description" id="description" class="form-control" placeholder="{{ __('barcode.setting_description') }}" rows="3">{{ $barcode->description }}</textarea>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="is_continuous" id="is_continuous" value="1" {{ $barcode->is_continuous ? 'checked' : '' }}> @lang('barcode.is_continuous')
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="top_margin">{{ __('barcode.top_margin') }} ({{ __('barcode.in_in') }}):*</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
                </span>
                <input type="number" name="top_margin" id="top_margin" class="form-control" placeholder="{{ __('barcode.top_margin') }}" min="0" step="0.01" required value="{{ $barcode->top_margin }}">
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="left_margin">{{ __('barcode.left_margin') }} ({{ __('barcode.in_in') }}):*</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                </span>
                <input type="number" name="left_margin" id="left_margin" class="form-control" placeholder="{{ __('barcode.left_margin') }}" min="0" step="0.01" required value="{{ $barcode->left_margin }}">
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="width">{{ __('barcode.width') }} ({{ __('barcode.in_in') }}):*</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-text-width" aria-hidden="true"></i>
                </span>
                <input type="number" name="width" id="width" class="form-control" placeholder="{{ __('barcode.width') }}" min="0.1" step="0.01" required value="{{ $barcode->width }}">
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="height">{{ __('barcode.height') }} ({{ __('barcode.in_in') }}):*</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-text-height" aria-hidden="true"></i>
                </span>
                <input type="number" name="height" id="height" class="form-control" placeholder="{{ __('barcode.height') }}" min="0.1" step="0.01" required value="{{ $barcode->height }}">
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="paper_width">{{ __('barcode.paper_width') }} ({{ __('barcode.in_in') }}):*</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-text-width" aria-hidden="true"></i>
                </span>
                <input type="number" name="paper_width" id="paper_width" class="form-control" placeholder="{{ __('barcode.paper_width') }}" min="0.1" step="0.01" required value="{{ $barcode->paper_width }}">
              </div>
            </div>
          </div>
          <div class="col-sm-6 paper_height_div @if($barcode->is_continuous) hide @endif">
            <div class="form-group">
              <label for="paper_height">{{ __('barcode.paper_height') }} ({{ __('barcode.in_in') }}):*</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-text-height" aria-hidden="true"></i>
                </span>
                <input type="number" name="paper_height" id="paper_height" class="form-control" placeholder="{{ __('barcode.paper_height') }}" min="0.1" step="0.01" required value="{{ $barcode->paper_height }}">
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="stickers_in_one_row">{{ __('barcode.stickers_in_one_row') }}:*</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </span>
                <input type="number" name="stickers_in_one_row" id="stickers_in_one_row" class="form-control" placeholder="{{ __('barcode.stickers_in_one_row') }}" min="1" required value="{{ $barcode->stickers_in_one_row }}">
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="row_distance">{{ __('barcode.row_distance') }} ({{ __('barcode.in_in') }}):*</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
                </span>
                <input type="number" name="row_distance" id="row_distance" class="form-control" placeholder="{{ __('barcode.row_distance') }}" min="0" step="0.01" required value="{{ $barcode->row_distance }}">
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="col_distance">{{ __('barcode.col_distance') }} ({{ __('barcode.in_in') }}):*</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true"></span>
                </span>
                <input type="number" name="col_distance" id="col_distance" class="form-control" placeholder="{{ __('barcode.col_distance') }}" min="0" step="0.01" required value="{{ $barcode->col_distance }}">
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6 stickers_per_sheet_div @if($barcode->is_continuous) hide @endif">
            <div class="form-group">
              <label for="stickers_in_one_sheet">{{ __('barcode.stickers_in_one_sheet') }}:*</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-th" aria-hidden="true"></i>
                </span>
                <input type="number" name="stickers_in_one_sheet" id="stickers_in_one_sheet" class="form-control" placeholder="{{ __('barcode.stickers_in_one_sheet') }}" min="1" required value="{{ $barcode->stickers_in_one_sheet }}">
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-12">
            <button type="submit" class="btn btn-primary pull-right">@lang('messages.update')</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</section>
<!-- /.content -->
@endsection
