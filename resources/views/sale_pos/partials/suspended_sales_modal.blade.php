<!-- Edit Order tax Modal -->
<div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">@lang('lang_v1.suspended_sales')</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				@php
				$c = 0;
				@endphp
				@forelse($sales as $sale)
				@if($sale->is_suspend)
				<div class="col-xs-6 col-sm-3">
					<div class="small-box bg-yellow">
						<div class="inner text-center">
							@if(!empty($sale->additional_notes))
							<p><i class="fa fa-edit"></i> {{$sale->additional_notes}}</p>
							@endif
							<p>{{$sale->invoice_no}}<br>
								{{@format_date($sale->transaction_date)}}<br>
								<strong><i class="fa fa-user"></i> {{$sale->name}}</strong>
							</p>
							<p><i class="fa fa-cubes"></i>@lang('lang_v1.total_items'): {{count($sale->sell_lines)}}<br>
								<i class="fa fa-money"></i> @lang('sale.total'): <span class="display_currency" data-currency_symbol=true>{{$sale->final_total}}</span>
							</p>
							@if($is_tables_enabled && !empty($sale->table->name))
							@lang('restaurant.table'): {{$sale->table->name}}
							@endif
							@if($is_service_staff_enabled && !empty($sale->service_staff))
							<br>@lang('restaurant.service_staff'): {{$sale->service_staff->user_full_name}}
							@endif
						</div>
						<a href="{{route('pos.edit', ['id' => $sale->id, 'name' => $sale->additional_notes])}}" class="small-box-footer" id="pop_up_modal">
							@lang('sale.edit_sale') <i class="fa fa-arrow-circle-right"></i>
						</a>
					</div>
				</div>
				@php
				$c++;
				@endphp
				@endif

				@if($c%4==0)
				<div class="clearfix"></div>
				@endif
				@empty
				<p class="text-center">@lang('purchase.no_records_found')</p>
				@endforelse
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
		</div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
