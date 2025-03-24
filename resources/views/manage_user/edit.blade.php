@extends('layouts.app')

@section('title', __( 'user.edit_user' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'user.edit_user' )</h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary'])
    <form action="{{ route('users.update', [$user->id]) }}" method="POST" id="user_edit_form">
      @csrf
      @method('PUT')
      
      <div class="col-md-2">
          <div class="form-group">
              <label for="surname">{{ __( 'business.prefix' ) }}:</label>
              <input type="text" name="surname" value="{{ $user->surname }}" class="form-control" placeholder="{{ __( 'business.prefix_placeholder' ) }}">
          </div>
      </div>
      
      <div class="col-md-5">
          <div class="form-group">
              <label for="first_name">{{ __( 'business.first_name' ) }}:*</label>
              <input type="text" name="first_name" value="{{ $user->first_name }}" class="form-control" required placeholder="{{ __( 'business.first_name' ) }}">
          </div>
      </div>
      
      <div class="col-md-5">
          <div class="form-group">
              <label for="last_name">{{ __( 'business.last_name' ) }}:</label>
              <input type="text" name="last_name" value="{{ $user->last_name }}" class="form-control" placeholder="{{ __( 'business.last_name' ) }}">
          </div>
      </div>
      
      <div class="clearfix"></div>
      
      <div class="col-md-12">
          <div class="form-group">
              <label for="email">{{ __( 'business.email' ) }}:*</label>
              <input id="email" type="email" name="email" value="{{ $user->email }}" class="form-control" required placeholder="{{ __( 'business.email' ) }}">
          </div>
      </div>
      
      <div class="col-md-12">
          <div class="form-group">
              <label for="role">{{ __( 'user.role' ) }}:*</label>
              <select name="role" class="form-control select2">
                  @foreach($roles as $id => $role)
                      <option value="{{ $id }}" {{ $user->roles->first()->id == $id ? 'selected' : '' }}>{{ $role }}</option>
                  @endforeach
              </select>
          </div>
      </div>
      
      <div class="col-md-6">
          <div class="form-group">
              <label for="password">{{ __( 'business.password' ) }}:</label>
              <input id="password" type="password" name="password" class="form-control" placeholder="{{ __( 'business.password' ) }}">
              <p class="help-block">@lang('user.leave_password_blank')</p>
          </div>
      </div>
      
      <div class="col-md-6">
          <div class="form-group">
              <label for="confirm_password">{{ __( 'business.confirm_password' ) }}:</label>
              <input type="password" name="confirm_password" class="form-control" placeholder="{{ __( 'business.confirm_password' ) }}">
          </div>
      </div>
      
      <div class="clearfix"></div>
      
      <div class="col-md-4">
          <div class="form-group">
              <label for="cmmsn_percent">{{ __( 'lang_v1.cmmsn_percent' ) }}:</label>
              @show_tooltip(__('lang_v1.commsn_percent_help'))
              <input type="number" name="cmmsn_percent" value="{{ $user->cmmsn_percent }}" class="form-control" placeholder="{{ __( 'lang_v1.cmmsn_percent' ) }}" step="0.01">
          </div>
      </div>
      
      <div class="col-md-4">
          <div class="form-group">
              <div class="checkbox">
                  <br/>
                  <label>
                      <input type="checkbox" name="selected_contacts" value="1" class="input-icheck" id="selected_contacts" {{ $user->selected_contacts ? 'checked' : '' }}>
                      {{ __( 'lang_v1.enable_selected_contacts' ) }}
                  </label>
                  @show_tooltip(__('lang_v1.tooltip_enable_selected_contacts'))
              </div>
          </div>
      </div>
      
      <div class="col-sm-4 selected_contacts_div @if(!$user->selected_contacts) hide @endif">
          <div class="form-group">
              <label for="selected_contacts">{{ __('lang_v1.selected_contacts') }}:</label>
              <select name="selected_contact_ids[]" class="form-control select2" multiple style="width: 100%;">
                  @foreach($contacts as $id => $contact)
                      <option value="{{ $id }}" {{ in_array($id, $contact_access) ? 'selected' : '' }}>{{ $contact }}</option>
                  @endforeach
              </select>
          </div>
      </div>
      
      <div class="clearfix"></div>
      
      <div class="col-md-4">
          <div class="form-group">
              <div class="checkbox">
                  <label>
                      <input type="checkbox" name="is_active" value="active" class="input-icheck status" {{ $is_checked_checkbox ? 'checked' : '' }}>
                      {{ __('lang_v1.status_for_user') }}
                  </label>
                  @show_tooltip(__('lang_v1.tooltip_enable_user_active'))
              </div>
          </div>
      </div>
      
      <div class="clearfix"></div>
      
      <div class="col-md-12">
          <button type="submit" class="btn btn-primary pull-right" id="submit_user_button">@lang( 'messages.update' )</button>
      </div>
  </form>
  
    @endcomponent
  @stop
@section('javascript')
<script type="text/javascript">
  $(document).ready(function(){
    $('#selected_contacts').on('ifChecked', function(event){
      $('div.selected_contacts_div').removeClass('hide');
    });
    $('#selected_contacts').on('ifUnchecked', function(event){
      $('div.selected_contacts_div').addClass('hide');
    });
  });

  $('form#user_edit_form').validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    email: {
                        email: true,
                        remote: {
                            url: "/business/register/check-email",
                            type: "post",
                            data: {
                                email: function() {
                                    return $( "#email" ).val();
                                },
                                user_id: {{$user->id}}
                            }
                        }
                    },
                    password: {
                        minlength: 5
                    },
                    confirm_password: {
                        equalTo: "#password",
                    }
                },
                messages: {
                    password: {
                        minlength: 'Password should be minimum 5 characters',
                    },
                    confirm_password: {
                        equalTo: 'Should be same as password'
                    },
                    username: {
                        remote: 'Invalid username or User already exist'
                    },
                    email: {
                        remote: '{{ __("validation.unique", ["attribute" => __("business.email")]) }}'
                    }
                }
            });
</script>
@endsection