@extends('layouts.app')
@section('title', __('printer.add_printer'))

@section('content')

<section class="content-header">
  <h1>@lang('printer.add_printer')</h1>
</section>

<section class="content">
  <form action="{{ route('printers.store') }}" method="POST" id="add_printer_form">
    @csrf
    <div class="box box-solid">
      <div class="box-body">
        <div class="row">

          <div class="col-sm-12">
            <div class="form-group">
              <label for="name">{{ __('printer.name') }}:*</label>
              <input type="text" class="form-control" id="name" name="name" required placeholder="{{ __('lang_v1.printer_name_help') }}">
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <label for="connection_type">{{ __('printer.connection_type') }}:*</label>
              <select name="connection_type" id="connection_type" class="form-control select2">
                @foreach($connection_types as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <label for="capability_profile">{{ __('printer.capability_profile') }}:*</label>
              @show_tooltip(__('tooltip.capability_profile'))
              <select name="capability_profile" id="capability_profile" class="form-control select2">
                @foreach($capability_profiles as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <label for="char_per_line">{{ __('printer.character_per_line') }}:*</label>
              <input type="number" class="form-control" id="char_per_line" name="char_per_line" value="42" required placeholder="{{ __('lang_v1.char_per_line_help') }}">
            </div>
          </div>

          <div class="col-sm-12" id="ip_address_div">
            <div class="form-group">
              <label for="ip_address">{{ __('printer.ip_address') }}:*</label>
              <input type="text" class="form-control" id="ip_address" name="ip_address" required placeholder="{{ __('lang_v1.ip_address_help') }}">
            </div>
          </div>

          <div class="col-sm-12" id="port_div">
            <div class="form-group">
              <label for="port">{{ __('printer.port') }}:*</label>
              <input type="text" class="form-control" id="port" name="port" value="9100" required>
              <span class="help-block">@lang('lang_v1.port_help')</span>
            </div>
          </div>

          <div class="col-sm-12 hide" id="path_div">
            <div class="form-group">
              <label for="path">{{ __('printer.path') }}:*</label>
              <input type="text" class="form-control" id="path" name="path" required>
              <span class="help-block">
                <b>Connection Type Windows:</b> The device files will be along the lines of <code>LPT1</code> (parallel) or <code>COM1</code> (serial). <br />
                <b>Connection Type Linux:</b> Your printer device file will be somewhere like <code>/dev/lp0</code> (parallel), <code>/dev/usb/lp1</code> (USB), <code>/dev/ttyUSB0</code> (USB-Serial), <code>/dev/ttyS0</code> (serial).
              </span>
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
