@inject('request', 'Illuminate\Http\Request')

<div class="container-fluid">

	<!-- Language changer -->
	<div class="row">
		<div class="col-md-12">

		</div>
	</div>

	<div class="row">


		<h1 class="text-center  page-header">{{ config('app.name', 'ultimatePOS') }}</h1>

		<div class="header-right-div">
			@if(!($request->segment(1) == 'business' && $request->segment(2) == 'register'))

			<!-- Register Url -->
			@if(env('ALLOW_REGISTRATION', true))
			<!-- <a
	            		href="{{ route('business.getRegister') }}@if(!empty(request()->lang)){{'?lang=' . request()->lang}} @endif"
	            		class="btn bg-maroon btn-flat margin"
	            	><b>{{ __('business.not_yet_registered')}}</b> {{ __('business.register_now') }}</a> -->

			<!-- pricing url -->
			@if(Route::has('pricing') && config('app.env') != 'demo' && $request->segment(1) != 'pricing')
			<a href="{{ action('\Modules\Superadmin\Http\Controllers\PricingController@index') }}">@lang('superadmin::lang.pricing')</a>
			@endif
			@endif
			@endif

			@if(!($request->segment(1) == 'business' && $request->segment(2) == 'register') && $request->segment(1) != 'login')
			{{ __('business.already_registered')}} <a href="{{ action('Auth\LoginController@login') }}@if(!empty(request()->lang)){{'?lang=' . request()->lang}} @endif">{{ __('business.sign_in') }}</a>
			@endif
		</div>
	</div>
</div>
