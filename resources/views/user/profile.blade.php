@extends('layouts.app')
@section('title', __('lang_v1.my_profile'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('lang_v1.my_profile')</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-sm-6">
            <form action="{{ route('user.updateProfile') }}" method="POST" id="edit_user_profile_form" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('user.edit_profile') }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="surname" class="col-sm-3 control-label">{{ __('business.prefix') }}:</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <input type="text" name="surname" id="surname" class="form-control" placeholder="{{ __('business.prefix_placeholder') }}" value="{{ old('surname', $user->surname) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="first_name" class="col-sm-3 control-label">{{ __('business.first_name') }}:</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="{{ __('business.first_name') }}" value="{{ old('first_name', $user->first_name) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="col-sm-3 control-label">{{ __('business.last_name') }}:</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="{{ __('business.last_name') }}" value="{{ old('last_name', $user->last_name) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">{{ __('business.email') }}:</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('business.email') }}" value="{{ old('email', $user->email) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="language" class="col-sm-3 control-label">{{ __('business.language') }}:</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <select name="language" id="language" class="form-control select2">
                                        @foreach($languages as $key => $value)
                                        <option value="{{ $key }}" {{ $user->language == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right">{{ __('messages.update') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-6">
            <form action="{{ route('user.updatePassword') }}" method="POST" id="edit_password_form" class="form-horizontal">
                @csrf
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('user.change_password') }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="current_password" class="col-sm-3 control-label">{{ __('user.current_password') }}:</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="{{ __('user.current_password') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_password" class="col-sm-3 control-label">{{ __('user.new_password') }}:</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="{{ __('user.new_password') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="col-sm-3 control-label">{{ __('user.confirm_new_password') }}:</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="{{ __('user.confirm_new_password') }}" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right">{{ __('messages.update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</section>
<!-- /.content -->

@endsection
