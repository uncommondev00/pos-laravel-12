<div class="modal-dialog" role="document">
  <div class="modal-content">
      <form action="{{ route('variation-templates.update', [$variation->id]) }}" 
            method="POST" 
            id="variation_edit_form" 
            class="form-horizontal">
          @csrf
          @method('PUT')

          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">@lang('lang_v1.edit_variation')</h4>
          </div>

          <div class="modal-body">
              <div class="form-group">
                  <label for="name" class="col-sm-3 control-label">
                      {{ __('lang_v1.variation_name') }}:*
                  </label>

                  <div class="col-sm-9">
                      <input type="text" 
                             name="name" 
                             value="{{ $variation->name }}" 
                             class="form-control" 
                             required 
                             placeholder="{{ __('lang_v1.variation_name') }}">
                  </div>
              </div>

              <div class="form-group">
                  <label class="col-sm-3 control-label">
                      {{ __('lang_v1.add_variation_values') }}:*
                  </label>
                  @foreach($variation->values as $attr)
                      @if($loop->first)
                          <div class="col-sm-7">
                              <input type="text" 
                                     name="edit_variation_values[{{ $attr->id }}]" 
                                     value="{{ $attr->name }}" 
                                     class="form-control" 
                                     required>
                          </div>
                      @endif
                  @endforeach
                  <div class="col-sm-2">
                      <button type="button" 
                              class="btn btn-primary" 
                              id="add_variation_values">+</button>
                  </div>
              </div>

              <div id="variation_values">
                  @foreach($variation->values as $attr)
                      @if(!$loop->first)
                          <div class="form-group">
                              <div class="col-sm-7 col-sm-offset-3">
                                  <input type="text" 
                                         name="edit_variation_values[{{ $attr->id }}]" 
                                         value="{{ $attr->name }}" 
                                         class="form-control" 
                                         required>
                              </div>
                          </div>
                      @endif
                  @endforeach
              </div>
          </div>

          <div class="modal-footer">
              <button type="submit" class="btn btn-primary">
                  @lang('messages.update')
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