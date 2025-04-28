@extends('layouts.app')
@section('title', __('lang_v1.'.$type.'s'))

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Contacts</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th wire:click="sortBy('contact_id')" style="cursor: pointer;">
                        Contact ID
                    </th>
                    <th wire:click="sortBy('supplier_business_name')" style="cursor: pointer;">
                        Business Name
                    </th>
                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                         Name
                    </th>
                    <th wire:click="sortBy('mobile')" style="cursor: pointer;" >
                        Mobile
                    </th>
                    <th class="text-center">
                        Total Purchase Due
                    </th>
                    <th class="text-center">
                        Total Purchase Return Due
                    </th>
                    
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                <tr>
                    <td>{{$contact->contact_id}}</td>
                    <td>{{$contact->supplier_business_name}}</td>
                    <td>{{$contact->name}}</td>
                    <td>{{$contact->mobile}}</td>
                    <td>
                        <span class="display_currency contact_due" data-orig-value="{{$contact->total_purchase - $contact->purchase_paid}}" data-currency_symbol=true data-highlight=false>{{$contact->total_purchase - $contact->purchase_paid}}</span>
                    </td>
                    <td>
                        <span class="display_currency return_due" data-orig-value="{{$contact->total_purchase_return - $contact->purchase_return_paid}}" data-currency_symbol=true data-highlight=false>{{$contact->total_purchase_return - $contact->purchase_return_paid}}</span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                                data-toggle="dropdown" aria-expanded="false">@lang("messages.actions")<span class="caret"></span><span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                @if(($contact->total_purchase + $contact->opening_balance - $contact->purchase_paid - $contact->opening_balance_paid) > 0)
                                    <li><a href="{{route('payments.getPayContactDue', [$contact->id])}}?type=purchase" class="pay_purchase_due"><i class="fa fa-money" aria-hidden="true"></i>@lang("contact.pay_due_amount")</a></li>
                                @endif
                                @if(($contact->total_purchase_return - $contact->purchase_return_paid) > 0)
                                    <li><a href="{{route('payments.getPayContactDue', [$contact->id])}}?type=purchase_return" class="pay_purchase_due"><i class="fa fa-money" aria-hidden="true"></i>@lang("lang_v1.receive_purchase_return_due")</a></li>
                                @endif
                                @can("supplier.view")
                                    <li><a href="{{route('contacts.show', [$contact->id])}}"><i class="fa fa-external-link" aria-hidden="true"></i>@lang("messages.view")</a></li>
                                @endcan
                                @can("supplier.update")
                                    <li><a href="{{route('contacts.edit', [$contact->id])}}" class="edit_contact_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a></li>
                                @endcan
                                @can("supplier.delete")
                                    <li><a href="#" wire:click.prevent="deleteContact({{ $contact->id }})" class="delete_contact_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</a></li>
                                @endcan
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('javascript')
    @livewireScripts
    
@endsection
