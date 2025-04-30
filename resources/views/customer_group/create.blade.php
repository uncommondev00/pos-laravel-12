<div class="modal-dialog" role="document">
  <div class="modal-content">
    <form action="{{ route('customer-group.store') }}" method="POST" id="customer_group_add_form">
      @csrf

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">@lang('lang_v1.add_customer_group')</h4>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="name">@lang('lang_v1.customer_group_name')*</label>
          <input type="text" id="name" name="name" class="form-control" required 
                 placeholder="@lang('lang_v1.customer_group_name')">
        </div>

        <div class="form-group">
          <label for="amount">@lang('lang_v1.calculation_percentage')</label>
          @show_tooltip(__('lang_v1.tooltip_calculation_percentage'))
          <input type="number" id="amount" name="amount" class="form-control" 
                 placeholder="@lang('lang_v1.calculation_percentage')" 
                 max="100" min="-100" step="0.1">
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
      </div>
    </form>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->