<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ route('brands.store') }}"
            method="POST"
            id="{{ $quick_add ? 'quick_add_brand_form' : 'brand_add_form' }}">
            @csrf

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ __('brand.add_brand') }}</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="name">{{ __('brand.brand_name') }}:*</label>
                    <input type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        required
                        placeholder="{{ __('brand.brand_name') }}">
                </div>

                <div class="form-group">
                    <label for="description">{{ __('brand.short_description') }}:</label>
                    <input type="text"
                        name="description"
                        id="description"
                        class="form-control"
                        placeholder="{{ __('brand.short_description') }}">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.close') }}</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
