@extends('layouts.app')
@section('title', __('barcode.add_barcode_setting'))

@section('content')
<style type="text/css">

</style>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>@lang('barcode.add_barcode_setting')</h1>
</section>

<!-- Main content -->
<section class="content">
  <form action="{{ route('barcodes.store') }}" method="POST" id="add_barcode_settings_form">
    @csrf
    <div class="box box-solid">
      <div class="box-body">
        <div class="row">

          <div class="col-sm-12">
            <div class="form-group">
              <label for="name">@lang('barcode.setting_name') *</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="@lang('barcode.setting_name')" required>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <label for="description">@lang('barcode.setting_description')</label>
              <textarea class="form-control" id="description" name="description" rows="3" placeholder="@lang('barcode.setting_description')"></textarea>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" id="is_continuous" name="is_continuous" value="1"> @lang('barcode.is_continuous')
                </label>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="top_margin">@lang('barcode.top_margin') (@lang('barcode.in_in')) *</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
                </span>
                <input type="number" class="form-control" id="top_margin" name="top_margin" placeholder="@lang('barcode.top_margin')" min="0" step="0.00001" value="0" required>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="left_margin">@lang('barcode.left_margin') (@lang('barcode.in_in')) *</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                </span>
                <input type="number" class="form-control" id="left_margin" name="left_margin" placeholder="@lang('barcode.left_margin')" min="0" step="0.00001" value="0" required>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="width">@lang('barcode.width') (@lang('barcode.in_in')) *</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-text-width" aria-hidden="true"></i>
                </span>
                <input type="number" class="form-control" id="width" name="width" placeholder="@lang('barcode.width')" min="0.1" step="0.00001" required>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="height">@lang('barcode.height') (@lang('barcode.in_in')) *</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-text-height" aria-hidden="true"></i>
                </span>
                <input type="number" class="form-control" id="height" name="height" placeholder="@lang('barcode.height')" min="0.1" step="0.00001" required>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="paper_width">@lang('barcode.paper_width') (@lang('barcode.in_in')) *</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-text-width" aria-hidden="true"></i>
                </span>
                <input type="number" class="form-control" id="paper_width" name="paper_width" placeholder="@lang('barcode.paper_width')" min="0.1" step="0.00001" required>
              </div>
            </div>
          </div>

          <div class="col-sm-6 paper_height_div">
            <div class="form-group">
              <label for="paper_height">@lang('barcode.paper_height') (@lang('barcode.in_in')) *</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-text-height" aria-hidden="true"></i>
                </span>
                <input type="number" class="form-control" id="paper_height" name="paper_height" placeholder="@lang('barcode.paper_height')" min="0.1" step="0.00001" required>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="stickers_in_one_row">@lang('barcode.stickers_in_one_row') *</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </span>
                <input type="number" class="form-control" id="stickers_in_one_row" name="stickers_in_one_row" placeholder="@lang('barcode.stickers_in_one_row')" min="1" required>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="row_distance">@lang('barcode.row_distance') (@lang('barcode.in_in')) *</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
                </span>
                <input type="number" class="form-control" id="row_distance" name="row_distance" placeholder="@lang('barcode.row_distance')" min="0" step="0.00001" value="0" required>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="col_distance">@lang('barcode.col_distance') (@lang('barcode.in_in')) *</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true"></span>
                </span>
                <input type="number" class="form-control" id="col_distance" name="col_distance" placeholder="@lang('barcode.col_distance')" min="0" step="0.00001" value="0" required>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-6 stickers_per_sheet_div">
            <div class="form-group">
              <label for="stickers_in_one_sheet">@lang('barcode.stickers_in_one_sheet') *</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-th" aria-hidden="true"></i>
                </span>
                <input type="number" class="form-control" id="stickers_in_one_sheet" name="stickers_in_one_sheet" placeholder="@lang('barcode.stickers_in_one_sheet')" min="1" required>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-6">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="is_default" value="1"> @lang('barcode.set_as_default')
                </label>
              </div>
            </div>
          </div>

          <div class="col-sm-12">
            <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
          </div>

        </div>
      </div>
    </div>
  </form>
</section>
@endsection
