<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1> @lang('lang_v1.'.$type.'s')
        <small>@lang( 'contact.manage_your_contact', ['contacts' =>  __('lang_v1.'.$type.'s') ])</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <input type="hidden" value="{{$type}}" id="contact_type">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'contact.all_your_contact', ['contacts' => __('lang_v1.'.$type.'s') ])])
        @if(auth()->user()->can('supplier.create') || auth()->user()->can('customer.create'))
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                    data-href="{{route('contacts.create', ['type' => $type])}}" 
                    data-container=".contact_modal">
                    <i class="fa fa-plus"></i> @lang('messages.add')</button>
                </div>
            @endslot
        @endif
        @if(auth()->user()->can('supplier.view') || auth()->user()->can('customer.view'))
            <div class="table-responsive">
                <input type="text" wire:model.live="search" class="form-control mb-3" placeholder="Search users...">

                     <!-- Per Page Dropdown -->
                    <div class="mb-3">
                        <label for="perPage">Show</label>
                        <select wire:model="perPage" wire:change="updatePerPage" class="form-control d-inline w-auto">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                        <span>entries</span>
                    </div>
                <table class="table table-bordered table-striped" >
                    <thead>
                        <tr>
                            <th>@lang('lang_v1.contact_id')</th>
                            @if($type == 'supplier') 
                                <th>@lang('business.business_name')</th>
                                <th>@lang('contact.name')</th>
                                <th>@lang('contact.contact')</th>
                                <th>@lang('contact.total_purchase_due')</th>
                                <th>@lang('lang_v1.total_purchase_return_due')</th>
                                <th>@lang('messages.action')</th>
                            @elseif( $type == 'customer')
                                <th>@lang('user.name')</th>
                                <th>@lang('lang_v1.customer_group')</th>
                                <th>@lang('business.address')</th>
                                <th>@lang('contact.contact')</th>
                                <th>Total Points</th>
                                <th>Can Gain Points</th>
                                <th>@lang('contact.total_sale_due')</th>
                                <th>@lang('lang_v1.total_sell_return_due')</th>
                                <th>@lang('messages.action')</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $contact)
                        @if($type == 'supplier') 
                            <tr>
                                <td>{{$contact->contact_id}}</td>
                                <td>{{$contact->supplier_business_name}}</td>
                                <td>{{$contact->name}}</td>
                                <td>{{$contact->mobile}}</td>
                                <td>
                                    <span class="display_currency contact_due" data-orig-value="{{$contact->total_purchase - $contact->purchase_paid}}" data-currency_symbol=true data-highlight=false>{{$contact->total_purchase - $contact->purchase_paid}}</span>
                                </td>
                                <td>
                                    <span class="display_currency return_due" data-orig-value="{{$contact->total_purchase_return - $contact->purchase_return_paid}}" data-currency_symbol=true data-highlight=false>{{$contact->total_purchase_return - $contact->purchase_return_paid }}</span>
                                </td>
                                <td>
                                   
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                                            data-toggle="dropdown" aria-expanded="false">@lang("messages.actions")<span class="caret"></span><span class="sr-only">Toggle Dropdown
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                    @if(($contact->total_purchase + $contact->opening_balance - $contact->purchase_paid - $contact->opening_balance_paid)  > 0)
                                        <li><a href="{{route('payments.getPayContactDue', [$contact->id])}}?type=purchase" class="pay_purchase_due"><i class="fa fa-money" aria-hidden="true"></i>@lang("contact.pay_due_amount")</a></li>
                                    @endif
                                    @if(($contact->total_purchase_return - $contact->purchase_return_paid)  > 0)
                                        <li><a href="{{route('payments.getPayContactDue', [$contact->id])}}?type=purchase_return" class="pay_purchase_due"><i class="fa fa-money" aria-hidden="true"></i>@lang("lang_v1.receive_purchase_return_due")</a></li>
                                    @endif
                                    @can("supplier.view")
                                        <li><a href="{{route('contacts.show', [$contact->id])}}"><i class="fa fa-external-link" aria-hidden="true"></i>@lang("messages.view")</a></li>
                                    @endcan
                                    @can("supplier.update")
                                        <li><a href="{{route('contacts.edit', [$contact->id])}}" class="edit_contact_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a></li>
                                    @endcan
                                    @can("supplier.delete")
                                        <li><a href="{{route('contacts.destroy', [$contact->id])}}" class="delete_contact_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</a></li>
                                    @endcan </ul></div>
                                </td>
                            </tr>
                        @elseif( $type == 'customer')
                            <tr>
                                <td>{{$contact->contact_id}}</td>
                                <td>{{$contact->name}}</td>
                                <td>{{$contact->customer_group}}</td>
                                <td>{{implode(array_filter([$contact->landmark, $contact->city, $contact->state, $contact->country]))}}</td>
                                <td>{{$contact->contact}}</td>
                                <td>{{$contact->points_value}}</td>
                                <td>{{$contact->points_status ? 'Yes' : 'No'}}</td>
                                <td class="display_currency" data-currency_symbol ="true">{{$contact->total_invoice - $contact->invoice_received}}</td>
                                <td class="display_currency" data-currency_symbol ="true">{{$contact->total_sell_return - $contact->sell_return_paid}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                                            data-toggle="dropdown" aria-expanded="false">@lang("messages.actions")<span class="caret"></span><span class="sr-only">Toggle Dropdown
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                    @if(($contact->total_invoice + $contact->opening_balance - $contact->total_invoice_received - $contact->opening_balance_paid)  > 0)
                                        <li><a href="{{route('payments.getPayContactDue', [$contact->id])}}?type=sell" class="pay_sale_due"><i class="fa fa-money" aria-hidden="true"></i>@lang("contact.pay_due_amount")</a></li>
                                    @endif
                                    @if(($contact->total_sell_return - $contact->sell_return_paid)  > 0)
                                        <li><a href="{{route('payments.getPayContactDue', [$contact->id])}}?type=sell_return" class="pay_purchase_due"><i class="fa fa-money" aria-hidden="true"></i>@lang("lang_v1.pay_sell_return_due")</a></li>
                                    @endif
                                    @can("customer.view")
                                        <li><a href="{{route('contacts.show', [$contact->id])}}"><i class="fa fa-external-link" aria-hidden="true"></i> @lang("messages.view")</a></li>
                                    @endcan
                                    @if($contact->id != 1 && $contact->id !=2)
                                    @can("customer.update")
                                        <li><a href="{{route('contacts.edit', [$contact->id])}}" class="edit_contact_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a></li>
                                    @endcan
                                    @endif
                                    @if($contact->id != 1 && $contact->id !=2)
                                    @if(!$contact->is_default)
                                    @can("customer.delete")
                                        <li><a href="{{route('contacts.destroy', [$contact->id])}}" class="delete_contact_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</a></li>
                                    @endcan
                                    @endif 
                                    @endif</ul></div>
                                </td>
                            </tr>
                        @endif
                        @empty
                            @if($type == 'supplier') 
                                <tr>
                                    <td colspan="6" class="text-center">No Data</td>
                                </tr>
                            @elseif( $type == 'customer')
                                <tr>
                                    <td colspan="9" class="text-center">No Data</td>
                                </tr>
                            @endif
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray font-17 text-center footer-total">
                            <td @if($type == 'supplier') colspan="4" @elseif( $type == 'customer') colspan="7" @endif><strong>@lang('sale.total'):</strong></td>
                            @if($type == 'supplier')
                            <td><span class="display_currency" id="footer_contact_due" data-currency_symbol ="true">{{$contacts->sum('total_purchase') - $contacts->sum('purchase_paid')}}</span></td>
                            <td><span class="display_currency" id="footer_contact_return_due" data-currency_symbol ="true">{{$contacts->sum('total_purchase_return') - $contacts->sum('purchase_return_paid')}}</span></td>
                            <td></td>
                            @elseif($type == 'customer')
                            <td><span class="display_currency" id="footer_contact_due" data-currency_symbol ="true">{{$contacts->sum('total_invoice') - $contacts->sum('invoice_received')}}</span></td>
                            <td><span class="display_currency" id="footer_contact_return_due" data-currency_symbol ="true">{{$contacts->sum('total_sell_return') - $contacts->sum('purchase_return_paid')}}</span></td>
                            <td></td>
                            @endif
                        </tr>
                    </tfoot>
                </table>
                <div class="mt-3">
                    {{ $contacts->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    @endcomponent

    <div class="modal fade contact_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade pay_contact_due_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
</div>


