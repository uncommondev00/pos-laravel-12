@extends('layouts.app')
@section('title', __('Add Points'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Add Points</h1>
</section>

<!-- Main content -->
<section class="content">

	<form action="{{ route('business.update_points') }}" method="POST">
		@csrf
		<div class="col-md-6">
			<div class="box box-warning">
				<!-- /.box-body -->
				<div class="box-body">
					<div class="form-group">
						<label for="points_amount">@lang('Amount') :*</label>
						<input
							type="text"
							id="points_amount"
							name="points_amount"
							class="form-control number"
							value="{{ old('points_amount', $points->points_amount) }}"
							required>
					</div>
					<div class="form-group">
						<label for="points">@lang('A percentage of the purchase amount to be credited to the points account of the customer') :*</label>
						<input
							type="text"
							id="points"
							name="points"
							class="form-control number"
							value="{{ old('points', $points->points) }}"
							required>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary pull-right">@lang('messages.update')</button>
				</div>
				<!-- /.box-body -->
			</div>
		</div>
	</form>

</section>

@endsection
