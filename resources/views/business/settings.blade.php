@extends('layouts.app')
@section('title', __('business.business_settings'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('business.business_settings')</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <form action="{{ route('business.postBusinessSettings') }}" method="POST" id="bussiness_edit_form" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-12 pos-tab-container">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pos-tab-menu">
                        <div class="list-group">
                            <a href="#" class="list-group-item text-center active">@lang('business.business')</a>
                            <a href="#" class="list-group-item text-center">
                                @lang('business.tax') @show_tooltip(__('tooltip.business_tax'))
                            </a>
                            <a href="#" class="list-group-item text-center">@lang('business.product')</a>
                            <a href="#" class="list-group-item text-center">@lang('business.sale')</a>
                            <a href="#" class="list-group-item text-center">@lang('sale.pos_sale')</a>
                            <a href="#" class="list-group-item text-center">@lang('purchase.purchases')</a>
                            @if(!config('constants.disable_expiry', true))
                            <a href="#" class="list-group-item text-center">@lang('business.dashboard')</a>
                            @endif
                            <a href="#" class="list-group-item text-center">@lang('business.system')</a>
                            <a href="#" class="list-group-item text-center">@lang('lang_v1.prefixes')</a>
                            <a href="#" class="list-group-item text-center">@lang('lang_v1.email_settings')</a>
                            <a href="#" class="list-group-item text-center">@lang('lang_v1.sms_settings')</a>
                            <a href="#" class="list-group-item text-center">@lang('lang_v1.modules')</a>
                        </div>
                    </div>

                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                        @include('business.partials.settings_business')
                        @include('business.partials.settings_tax')
                        @include('business.partials.settings_product')
                        @include('business.partials.settings_sales')
                        @include('business.partials.settings_pos')
                        @include('business.partials.settings_purchase')

                        @if(!config('constants.disable_expiry', true))
                        @include('business.partials.settings_dashboard')
                        @endif

                        @include('business.partials.settings_system')
                        @include('business.partials.settings_prefixes')
                        @include('business.partials.settings_email')
                        @include('business.partials.settings_sms')
                        @include('business.partials.settings_modules')
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-danger pull-right" type="submit">
                    @lang('business.update_settings')
                </button>
            </div>
        </div>
    </form>

</section>
<!-- /.content -->

@endsection
