<div class="modal-dialog modal-xl" role="document">
	<div class="modal-content">
		<form action="{{ route('opening-stocks.save') }}" method="post" id="add_opening_stock_form">
			@csrf
			<input type="hidden" name="product_id" value="{{ $product->id }}">
			<div class="modal-header">
				<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTitle">@lang('lang_v1.add_opening_stock')</h4>
			</div>
			<div class="modal-body">
				@include('opening_stock.form-part')
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="add_opening_stock_btn">@lang('messages.save')</button>
				<button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang('messages.close')</button>
			</div>
		</form>
	</div>
</div>
