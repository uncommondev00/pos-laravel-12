@extends('layouts.app')

@section('title', __( 'user.add_user' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>@lang( 'user.add_user' )</h1>
</section>

<!-- Main content -->
<section class="content">
  @component('components.widget', ['class' => 'box-primary'])
  <div class="row">
    <form action="{{ route('users.store') }}" method="post" id="user_add_form">
        @csrf
        <div class="col-md-2">
            <div class="form-group">
                <label for="surname">{{ __( 'business.prefix' ) }}:</label>
                <input type="text" name="surname" class="form-control" placeholder="{{ __( 'business.prefix_placeholder' ) }}">
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="first_name">{{ __( 'business.first_name' ) }}:*</label>
                <input type="text" name="first_name" class="form-control" required placeholder="{{ __( 'business.first_name' ) }}">
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="last_name">{{ __( 'business.last_name' ) }}:</label>
                <input type="text" name="last_name" class="form-control" placeholder="{{ __( 'business.last_name' ) }}">
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="email">{{ __( 'business.email' ) }}:*</label>
                <input id="email" type="email" name="email" class="form-control" required placeholder="{{ __( 'business.email' ) }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="role">{{ __( 'user.role' ) }}:*</label>
                <select name="role" class="form-control select2">
                    @foreach($roles as $key => $role)
                        <option value="{{ $key }}">{{ $role }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="username">{{ __( 'business.username' ) }}:</label>
                @if(!empty($username_ext))
                    <div class="input-group">
                        <input id="username" type="text" name="username" class="form-control" placeholder="{{ __( 'business.username' ) }}">
                        <span class="input-group-addon">{{ $username_ext }}</span>
                    </div>
                    <p class="help-block" id="show_username"></p>
                @else
                    <input type="text" name="username" class="form-control" placeholder="{{ __( 'business.username' ) }}">
                @endif
                <p class="help-block">@lang('lang_v1.username_help')</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="password">{{ __( 'business.password' ) }}:*</label>
                <input id="password" type="password" name="password" class="form-control" required placeholder="{{ __( 'business.password' ) }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="confirm_password">{{ __( 'business.confirm_password' ) }}:*</label>
                <input type="password" name="confirm_password" class="form-control" required placeholder="{{ __( 'business.confirm_password' ) }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="cmmsn_percent">{{ __( 'lang_v1.cmmsn_percent' ) }}:</label> @show_tooltip(__('lang_v1.commsn_percent_help'))
                <input type="number" name="cmmsn_percent" class="form-control" placeholder="{{ __( 'lang_v1.cmmsn_percent' ) }}" step="0.01">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox">
                    <br/>
                    <label>
                        <input type="checkbox" name="selected_contacts" value="1" class="input-icheck" id="selected_contacts"> {{ __( 'lang_v1.enable_selected_contacts' ) }}
                    </label>
                    @show_tooltip(__('lang_v1.tooltip_enable_selected_contacts'))
                </div>
            </div>
        </div>
        <div class="col-sm-4 hide selected_contacts_div">
            <div class="form-group">
                <label for="selected_contacts">{{ __('lang_v1.selected_contacts') }}:</label>
                <select name="selected_contact_ids[]" class="form-control select2" multiple style="width: 100%;">
                    @foreach($contacts as $key => $contact)
                        <option value="{{ $key }}">{{ $contact }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="is_active" value="active" class="input-icheck status" checked> {{ __('lang_v1.status_for_user') }}
                    </label>
                    @show_tooltip(__('lang_v1.tooltip_enable_user_active'))
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-right" id="submit_user_button">@lang( 'messages.save' )</button>
        </div>
    </form>
</div>

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

  $('form#user_add_form').validate({
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
                                }
                            }
                        }
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    confirm_password: {
                        equalTo: "#password"
                    },
                    username: {
                        minlength: 5,
                        remote: {
                            url: "/business/register/check-username",
                            type: "post",
                            data: {
                                username: function() {
                                    return $( "#username" ).val();
                                },
                                @if(!empty($username_ext))
                                  username_ext: "{{$username_ext}}"
                                @endif
                            }
                        }
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
  $('#username').change( function(){
    if($('#show_username').length > 0){
      if($(this).val().trim() != ''){
        $('#show_username').html("{{__('lang_v1.your_username_will_be')}}: <b>" + $(this).val() + "{{$username_ext}}</b>");
      } else {
        $('#show_username').html('');
      }
    }
  });
</script>
@endsection
