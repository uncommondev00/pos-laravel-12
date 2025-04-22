<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('invoice-schemes.update', [$invoice->id]) }}" method="POST" id="invoice_scheme_add_form">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">@lang('invoice.edit_invoice')</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="option-div-group">
            <div class="col-sm-4">
              <div class="form-group">
                <div class="option-div {{ $invoice->scheme_type == 'blank' ? 'active' : '' }}">
                  <h4>FORMAT: <br>XXXX <i class="fa fa-check-circle pull-right icon"></i></h4>
                  <input type="radio" name="scheme_type" value="blank" {{ $invoice->scheme_type == 'blank' ? 'checked' : '' }}>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <div class="option-div {{ $invoice->scheme_type == 'year' ? 'active' : '' }}">
                  <h4>FORMAT: <br>{{ date('Y') }}-XXXX <i class="fa fa-check-circle pull-right icon"></i></h4>
                  <input type="radio" name="scheme_type" value="year" {{ $invoice->scheme_type == 'year' ? 'checked' : '' }}>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              <label>@lang('invoice.preview'):</label>
              <div id="preview_format">@lang('invoice.not_selected')</div>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <label for="name">@lang('invoice.name') :*</label>
              <input type="text" name="name" id="name" class="form-control" value="{{ $invoice->name }}" required placeholder="@lang('invoice.name')">
            </div>
          </div>

          @php
          $disabled = '';
          $prefix = $invoice->prefix;
          if ($invoice->scheme_type == 'year') {
          $prefix = date('Y') . '-';
          $disabled = 'disabled';
          }
          @endphp

          <div id="invoice_format_settings">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="prefix">@lang('invoice.prefix') :</label>
                <div class="input-group col-md-12 col-sm-12">
                  <span class="input-group-addon">
                    <i class="fa fa-info"></i>
                  </span>
                  <input type="text" name="prefix" id="prefix" class="form-control" value="{{ $prefix }}" placeholder="" {{ $disabled }}>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label for="start_number">@lang('invoice.start_number') :</label>
                <div class="input-group col-md-12 col-sm-12">
                  <span class="input-group-addon">
                    <i class="fa fa-info"></i>
                  </span>
                  <input type="number" name="start_number" id="start_number" class="form-control" value="{{ $invoice->start_number }}" required min="0">
                </div>
              </div>
            </div>

            <div class="clearfix">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="total_digits">@lang('invoice.total_digits') :</label>
                  <div class="input-group col-md-12 col-sm-12">
                    <span class="input-group-addon">
                      <i class="fa fa-info"></i>
                    </span>
                    <select name="total_digits" id="total_digits" class="form-control" required>
                      @for ($i = 4; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ $invoice->total_digits == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                  </div>
                </div>
              </div>
            </div> <!-- /.clearfix -->

          </div> <!-- /#invoice_format_settings -->

        </div> <!-- /.row -->
      </div> <!-- /.modal-body -->

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
