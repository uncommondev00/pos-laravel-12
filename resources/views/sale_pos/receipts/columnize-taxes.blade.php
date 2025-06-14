@php
$totals = ['taxable_value' => 0];
@endphp

<table style="width:100%;">
	<thead>
		<tr>
			<td>

				@if(!empty($receipt_details->invoice_heading))
				<p class="text-right text-muted-imp" style="font-weight: bold; font-size: 18px !important">{!! $receipt_details->invoice_heading !!}</p>
				@endif

				<p class="text-right">
					@if(!empty($receipt_details->invoice_no_prefix))
					{!! $receipt_details->invoice_no_prefix !!}
					@endif

					{{$receipt_details->invoice_no}}
				</p>

			</td>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>

				@if(!empty($receipt_details->header_text))
				<div class="row invoice-info">
					<div class="col-xs-12">
						{!! $receipt_details->header_text !!}
					</div>
				</div>
				@endif

				<!-- business information here -->
				<div class="row invoice-info">

					<div class="col-md-6 invoice-col width-50 color-555">

						<!-- Logo -->
						@if(!empty($receipt_details->logo))
						<img src="{{$receipt_details->logo}}" class="img">
						<br />
						@endif

						<!-- Shop & Location Name  -->
						@if(!empty($receipt_details->display_name))
						<p>
							{{$receipt_details->display_name}}
							@if(!empty($receipt_details->address))
							<br />{!! $receipt_details->address !!}
							@endif

							@if(!empty($receipt_details->contact))
							<br />{{ $receipt_details->contact }}
							@endif

							@if(!empty($receipt_details->website))
							<br />{{ $receipt_details->website }}
							@endif

							@if(!empty($receipt_details->tax_info1))
							<br />{{ $receipt_details->tax_label1 }} {{ $receipt_details->tax_info1 }}
							@endif

							@if(!empty($receipt_details->tax_info2))
							<br />{{ $receipt_details->tax_label2 }} {{ $receipt_details->tax_info2 }}
							@endif

							@if(!empty($receipt_details->location_custom_fields))
							<br />{{ $receipt_details->location_custom_fields }}
							@endif
						</p>
						@endif

						<!-- Table information-->
						@if(!empty($receipt_details->table_label) || !empty($receipt_details->table))
						<p>
							@if(!empty($receipt_details->table_label))
							{!! $receipt_details->table_label !!}
							@endif
							{{$receipt_details->table}}
						</p>
						@endif

						<!-- Waiter info -->
						@if(!empty($receipt_details->service_staff_label) || !empty($receipt_details->service_staff))
						<p>
							@if(!empty($receipt_details->service_staff_label))
							{!! $receipt_details->service_staff_label !!}
							@endif
							{{$receipt_details->service_staff}}
						</p>
						@endif
					</div>

					<div class="col-md-6 invoice-col width-50">

						<p class="text-right font-30">
							@if(!empty($receipt_details->invoice_no_prefix))
							<span class="pull-left">{!! $receipt_details->invoice_no_prefix !!}</span>
							@endif

							{{$receipt_details->invoice_no}}
						</p>

						<!-- Total Due-->
						@if(!empty($receipt_details->total_due))
						<p class="bg-light-blue-active text-right font-23 padding-5">
							<span class="pull-left bg-light-blue-active">
								{!! $receipt_details->total_due_label !!}
							</span>

							{{$receipt_details->total_due}}
						</p>
						@endif

						@if(!empty($receipt_details->all_due))
						<p class="bg-light-blue-active text-right font-23 padding-5">
							<span class="pull-left bg-light-blue-active">
								{!! $receipt_details->all_bal_label !!}
							</span>

							{{$receipt_details->all_due}}
						</p>
						@endif

						<!-- Total Paid-->
						@if(!empty($receipt_details->total_paid))
						<p class="text-right font-23 color-555">
							<span class="pull-left">{!! $receipt_details->total_paid_label !!}</span>
							{{$receipt_details->total_paid}}
						</p>
						@endif
						<!-- Date-->
						@if(!empty($receipt_details->date_label))
						<p class="text-right font-23 color-555">
							<span class="pull-left">
								{{$receipt_details->date_label}}
							</span>

							{{$receipt_details->invoice_date}}
						</p>
						@endif
					</div>
				</div>

				<div class="row invoice-info color-555">
					<br />
					<div class="col-md-6 invoice-col width-50 word-wrap">
						<b>{{ $receipt_details->customer_label }}</b><br />

						<!-- customer info -->
						@if(!empty($receipt_details->customer_name))
						{{ $receipt_details->customer_name }} <br>
						@endif
						@if(!empty($receipt_details->customer_info))
						{!! $receipt_details->customer_info !!}
						@endif
						@if(!empty($receipt_details->client_id_label))
						<br />
						<strong>{{ $receipt_details->client_id_label }}</strong> {{ $receipt_details->client_id }}
						@endif
						@if(!empty($receipt_details->customer_tax_label))
						<br />
						<strong>{{ $receipt_details->customer_tax_label }}</strong> {{ $receipt_details->customer_tax_number }}
						@endif
						@if(!empty($receipt_details->customer_custom_fields))
						<br />{!! $receipt_details->customer_custom_fields !!}
						@endif
						@if(!empty($receipt_details->sales_person_label))
						<br />
						<strong>{{ $receipt_details->sales_person_label }}</strong> {{ $receipt_details->sales_person }}
						@endif
					</div>


					<div class="col-md-6 invoice-col width-50 word-wrap">
						<p>
							@if(!empty($receipt_details->sub_heading_line1))
							{{ $receipt_details->sub_heading_line1 }}
							@endif
							@if(!empty($receipt_details->sub_heading_line2))
							<br>{{ $receipt_details->sub_heading_line2 }}
							@endif
							@if(!empty($receipt_details->sub_heading_line3))
							<br>{{ $receipt_details->sub_heading_line3 }}
							@endif
							@if(!empty($receipt_details->sub_heading_line4))
							<br>{{ $receipt_details->sub_heading_line4 }}
							@endif
							@if(!empty($receipt_details->sub_heading_line5))
							<br>{{ $receipt_details->sub_heading_line5 }}
							@endif
						</p>
					</div>

				</div>

				<div class="row color-555">
					<div class="col-xs-12">
						<br />
						<table class="table table-bordered table-no-top-cell-border">
							<thead>
								<tr style="background-color: #357ca5 !important; color: white !important; font-size: 15px !important font-weight: bold;" class="table-no-side-cell-border table-no-top-cell-border text-center">
									<td style="background-color: #357ca5 !important; color: white !important;">#</td>

									<td style="background-color: #357ca5 !important; color: white !important;" class="text-left">
										{!! $receipt_details->table_product_label !!}
									</td>

									@if($receipt_details->show_cat_code == 1)
									<td style="background-color: #357ca5 !important; color: white !important;" class="text-right">{!! $receipt_details->cat_code_label !!}</td>
									@endif

									<td style="background-color: #357ca5 !important; color: white !important;" class="text-right">
										{!! $receipt_details->table_qty_label !!}
									</td>
									<td style="background-color: #357ca5 !important; color: white !important;" class="text-right">
										{!! $receipt_details->table_unit_price_label !!} <span class="small color-white"> ({{$receipt_details->currency['symbol']}})</span>
									</td>
									<!-- <td style="background-color: #357ca5 !important; color: white !important;">
						{!! $receipt_details->line_discount_label !!}
					</td> -->
									<td style="background-color: #357ca5 !important; color: white !important;" class="text-right">
										Taxable Value <span class="small color-white"> ({{$receipt_details->currency['symbol']}})</span>
									</td>

									@if(!empty($receipt_details->table_tax_headings))

									@foreach($receipt_details->table_tax_headings as $tax_heading)
									<td style="background-color: #357ca5 !important; color: white !important;" class="word-wrap text-right">
										{{$tax_heading}} <span class="small color-white"> ({{$receipt_details->currency['symbol']}})</span>
									</td>

									@php
									$totals[$tax_heading] = 0;
									@endphp
									@endforeach

									@endif

									<td style="background-color: #357ca5 !important; color: white !important;" class="text-right">
										{!! $receipt_details->table_subtotal_label !!} <span class="small color-white"> ({{$receipt_details->currency['symbol']}})</span>
									</td>
								</tr>
							</thead>
							<tbody>
								@foreach($receipt_details->lines as $line)
								<tr>
									<td class="text-center">
										{{$loop->iteration}}
									</td>
									<td class="text-left" style="word-break: break-all;">
										{{$line['name']}} {{$line['variation']}}
										@if(!empty($line['sub_sku'])), {{$line['sub_sku']}} @endif @if(!empty($line['brand'])), {{$line['brand']}} @endif
										@if(!empty($line['sell_line_note']))({{$line['sell_line_note']}}) @endif
										@if(!empty($line['lot_number']))<br> {{$line['lot_number_label']}}: {{$line['lot_number']}} @endif
										@if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}: {{$line['product_expiry']}} @endif
									</td>

									@if($receipt_details->show_cat_code == 1)
									<td class="text-right">
										@if(!empty($line['cat_code']))
										{{$line['cat_code']}}
										@endif
									</td>
									@endif

									<td class="text-right">
										{{$line['quantity']}} {{$line['units']}}
									</td>
									<td class="text-right">
										{{$line['unit_price_before_discount']}}
									</td>
									<!-- <td class="text-right">
							{{$line['line_discount']}}
						</td> -->
									<td class="text-right">
										<span class="display_currency" data-currency_symbol="false">
											{{$line['price_exc_tax']}}
										</span>

										@php
										$totals['taxable_value'] += $line['price_exc_tax'];
										@endphp
									</td>

									@if(!empty($receipt_details->table_tax_headings))

									@foreach($receipt_details->table_tax_headings as $tax_heading)
									<td class="text-right word-wrap">
										@if(!empty($line['group_tax_details']))

										@foreach($line['group_tax_details'] as $tax_detail)
										@if(strpos($tax_detail['name'], $tax_heading) !== FALSE)

										@php
										$totals[$tax_heading] += $tax_detail['calculated_tax'];
										@endphp

										<span class="display_currency" data-currency_symbol="false">
											{{$tax_detail['calculated_tax']}}
										</span>
										<br />
										<span class="small">
											{{$tax_detail['amount']}}%
										</span>
										@endif
										@endforeach

										@else
										@if(strpos($line['tax_name'], $tax_heading) !== FALSE)

										@php
										$totals[$tax_heading] += $line['tax_unformatted'];
										@endphp

										<span class="display_currency" data-currency_symbol="false">
											{{$line['tax_unformatted']}}
										</span>
										<br />
										<span class="small">
											{{$line['tax_percent']}}%
										</span>
										@endif
										@endif
									</td>
									@endforeach

									@endif

									<!-- @if(!empty($line->group_tax_details))

						@foreach($line->group_tax_details as $tax_detail)
							<td class="text-right">
								{{$line['line_discount']}}
							</td>
						@endforeach

						@endif -->

									<td class="text-right">
										{{$line['line_total']}}
									</td>
								</tr>
								{{-- @if(!empty($line['modifiers']))
						@foreach($line['modifiers'] as $modifier)
							<tr>
								<td class="text-center">
									&nbsp;
								</td>
								<td>
		                            {{$modifier['name']}} {{$modifier['variation']}}
								@if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif
								@if(!empty($modifier['sell_line_note']))({{$modifier['sell_line_note']}}) @endif
			</td>

			@if($receipt_details->show_cat_code == 1)
			<td>
				@if(!empty($modifier['cat_code']))
				{{$modifier['cat_code']}}
				@endif
			</td>
			@endif

			<td class="text-right">
				{{$modifier['quantity']}} {{$modifier['units']}}
			</td>
			<td class="text-right">
				&nbsp;
			</td>
			<td class="text-center">
				&nbsp;
			</td>
			<td class="text-center">
				&nbsp;
			</td>
			<td class="text-center">
				{{$modifier['unit_price_exc_tax']}}
			</td>
			<td class="text-right">
				{{$modifier['line_total']}}
			</td>
		</tr>
		@endforeach
		@endif --}}
		@endforeach

		@php
		$lines = count($receipt_details->lines);
		@endphp

		@for ($i = $lines; $i < 5; $i++)
			<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<!-- <td>&nbsp;</td> -->

			@if(!empty($receipt_details->table_tax_headings))
			@foreach($receipt_details->table_tax_headings as $tax_heading)
			<td>&nbsp;</td>
			@endforeach
			@endif

			@if($receipt_details->show_cat_code == 1)
			<td>&nbsp;</td>
			@endif
			</tr>
			@endfor
			<tr>
				@php
				$colspan = 4;
				@endphp
				@if($receipt_details->show_cat_code == 1)
				@php
				$colspan = 5;
				@endphp
				@endif

				<th colspan="{{$colspan}}" class="text-right"
					style="background-color: #d2d6de !important;">
					Total
				</th>
				<th class="text-right" style="background-color: #d2d6de !important;">
					<span class="display_currency" data-currency_symbol="false">
						{{$totals['taxable_value']}}
					</span>
				</th>

				<!-- <td>&nbsp;</td> -->

				@if(!empty($receipt_details->table_tax_headings))
				@foreach($receipt_details->table_tax_headings as $tax_heading)
				<th class="text-right" style="background-color: #d2d6de !important;">
					<span class="display_currency" data-currency_symbol="false">
						{{$totals[$tax_heading]}}
					</span>
				</th>
				@endforeach
				@endif

				<th class="text-right" style="background-color: #d2d6de !important;">
					<span class="display_currency" data-currency_symbol="false">
						{{$receipt_details->subtotal_unformatted}}
					</span>
				</th>
			</tr>
	</tbody>
</table>
</div>
</div>

<div class="row invoice-info color-555" style="page-break-inside: avoid !important">
	<div class="col-md-6 invoice-col width-50">
		<table class="table table-condensed">
			@if(!empty($receipt_details->payments))
			@foreach($receipt_details->payments as $payment)
			<tr>
				<td>{{$payment['method']}}</td>
				<td>{{$payment['amount']}}</td>
				<td>{{$payment['date']}}</td>
			</tr>
			@endforeach
			@endif
		</table>
		<b class="pull-left">Authorized Signatory</b>
	</div>

	<div class="col-md-6 invoice-col width-50">
		<table class="table-no-side-cell-border table-no-top-cell-border width-100">
			<tbody>
				<tr class="color-555">
					<td style="width:50%">
						{!! $receipt_details->subtotal_label !!}
					</td>
					<td class="text-right">
						{{$receipt_details->subtotal}}
					</td>
				</tr>

				<!-- Shipping Charges -->
				@if(!empty($receipt_details->shipping_charges))
				<tr class="color-555">
					<td style="width:50%">
						{!! $receipt_details->shipping_charges_label !!}
					</td>
					<td class="text-right">
						{{$receipt_details->shipping_charges}}
					</td>
				</tr>
				@endif

				<!-- Discount -->
				@if( !empty($receipt_details->discount) )
				<tr class="color-555">
					<td>
						{!! $receipt_details->discount_label !!}
					</td>

					<td class="text-right">
						(-) {{$receipt_details->discount}}
					</td>
				</tr>
				@endif

				@if(!empty($receipt_details->group_tax_details))
				@foreach($receipt_details->group_tax_details as $key => $value)
				<tr class="color-555">
					<td>
						{!! $key !!}
					</td>
					<td class="text-right">
						(+) {{$value}}
					</td>
				</tr>
				@endforeach
				@else
				@if( !empty($receipt_details->tax) )
				<tr class="color-555">
					<td>
						{!! $receipt_details->tax_label !!}
					</td>
					<td class="text-right">
						(+) {{$receipt_details->tax}}
					</td>
				</tr>
				@endif
				@endif


				<!-- Total -->
				<tr>
					<th style="background-color: #357ca5 !important; color: white !important" class="font-23 padding-10">
						{!! $receipt_details->total_label !!}
					</th>
					<td class="text-right font-23 padding-10" style="background-color: #357ca5 !important; color: white !important">
						{{$receipt_details->total}}
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="row color-555">
	<div class="col-xs-6">
		{{$receipt_details->additional_notes}}
	</div>
</div>
{{-- Barcode --}}
@if($receipt_details->show_barcode)
<br>
<div class="row">
	<div class="col-xs-12">
		<img class="center-block" src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2,30,array(39, 48, 54), true)}}">
	</div>
</div>
@endif

@if(!empty($receipt_details->footer_text))
<div class="row color-555">
	<div class="col-xs-12">
		{!! $receipt_details->footer_text !!}
	</div>
</div>
@endif

</td>
</tr>
</tbody>
</table>
