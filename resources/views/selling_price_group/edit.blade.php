<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ route('selling-price-group.update', [$spg->id]) }}"
            method="POST"
            id="selling_price_group_form">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ __('lang_v1.edit_selling_price_group') }}</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="name">{{ __('lang_v1.name') }}:*</label>
                    <input type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        required
                        placeholder="{{ __('lang_v1.name') }}"
                        value="{{ $spg->name }}">
                </div>

                <div class="form-group">
                    <label for="description">{{ __('lang_v1.description') }}:</label>
                    <textarea name="description"
                        id="description"
                        class="form-control"
                        placeholder="{{ __('lang_v1.description') }}"
                        rows="3">{{ $spg->description }}</textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.close') }}</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
