<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ route('categories.update', $category->id) }}"
            method="POST"
            id="category_edit_form">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ __('category.edit_category') }}</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="name">{{ __('category.category_name') }}:*</label>
                    <input type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        required
                        placeholder="{{ __('category.category_name') }}"
                        value="{{ $category->name }}">
                </div>

                <div class="form-group">
                    <label for="short_code">{{ __('category.code') }}:</label>
                    <input type="text"
                        name="short_code"
                        id="short_code"
                        class="form-control"
                        placeholder="{{ __('category.code') }}"
                        value="{{ $category->short_code }}">
                    <p class="help-block">{!! __('lang_v1.category_code_help') !!}</p>
                </div>

                @if(!empty($parent_categories))
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                name="add_as_sub_cat"
                                value="1"
                                class="toggler"
                                data-toggle_id="parent_cat_div"
                                {{ !$is_parent ? 'checked' : '' }}>
                            {{ __('category.add_as_sub_category') }}
                        </label>
                    </div>
                </div>
                <div class="form-group {{ $is_parent ? 'hide' : '' }}" id="parent_cat_div">
                    <label for="parent_id">{{ __('category.select_parent_category') }}:</label>
                    <select name="parent_id"
                        id="parent_id"
                        class="form-control">
                        @foreach($parent_categories as $key => $value)
                        <option value="{{ $key }}"
                            {{ $selected_parent == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.close') }}</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
