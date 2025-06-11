<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ route('variation-templates.store') }}"
            method="POST"
            id="variation_add_form"
            class="form-horizontal">
            @csrf

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@lang('lang_v1.add_variation')</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">
                        {{ __('lang_v1.variation_name') }}:*
                    </label>

                    <div class="col-sm-9">
                        <input type="text"
                            name="name"
                            class="form-control"
                            required
                            placeholder="{{ __('lang_v1.variation_name') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        {{ __('lang_v1.add_variation_values') }}:*
                    </label>
                    <div class="col-sm-7">
                        <input type="text"
                            name="variation_values[]"
                            class="form-control"
                            required>
                    </div>
                    <div class="col-sm-2">
                        <button type="button"
                            class="btn btn-primary"
                            id="add_variation_values">+</button>
                    </div>
                </div>

                <div id="variation_values"></div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    @lang('messages.save')
                </button>
                <button type="button"
                    class="btn btn-default"
                    data-dismiss="modal">
                    @lang('messages.close')
                </button>
            </div>
        </form>
    </div>
</div>
