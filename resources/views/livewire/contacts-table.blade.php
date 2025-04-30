<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <style>
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: auto;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .paginate_button {
            cursor: pointer;
        }

        .paginate_button.disabled {
            cursor: not-allowed;
        }
    </style>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> @lang('lang_v1.'.$type.'s')
            <small>@lang( 'contact.manage_your_contact', ['contacts' => __('lang_v1.'.$type.'s') ])</small>
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
            <div class="row mb-3">
                <div class="col-sm-12 col-md-6">
                    <div class="dataTables_length">
                        <label>
                            Show
                            <select wire:model.live="perPage" class="form-control form-control-sm" style="width: auto; display: inline-block;">
                                @foreach($perPageOptions as $option)
                                @if($option === -1)
                                <option value="{{ $option }}">All</option>
                                @else
                                <option value="{{ $option }}">{{ $option }}</option>
                                @endif
                                @endforeach
                            </select>
                            entries
                        </label>
                    </div>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="dataTables_filter">
                        <label>
                            Search:
                            <div class="input-group" style="display: inline-flex; width: auto;">
                                <input type="search"
                                    wire:model.live.debounce.500ms="search"
                                    class="form-control form-control-sm"
                                    placeholder="Type to search..."
                                    style="width: 200px;">
                                @if($search)
                                <div class="input-group-append">
                                    <button wire:click="$set('search', '')" class="btn btn-sm btn-default">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                        @if($type == 'supplier')
                        <tr>
                            <th wire:click="sortBy('contact_id')" style="cursor: pointer;" @if($sortField==='contact_id' ) class="{{ $sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc' }}" @else class="sorting" @endif>
                                Contact ID
                            </th>
                            <th wire:click="sortBy('supplier_business_name')" style="cursor: pointer;" @if($sortField==='supplier_business_name' ) class="{{ $sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc' }}" @else class="sorting" @endif>
                                Business Name
                            </th>
                            <th wire:click="sortBy('name')" style="cursor: pointer;" @if($sortField==='name' ) class="{{ $sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc' }}" @else class="sorting" @endif>
                                Name
                            </th>
                            <th wire:click="sortBy('mobile')" style="cursor: pointer;" @if($sortField==='mobile' ) class="{{ $sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc' }}" @else class="sorting" @endif>
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
                        @elseif($type == 'customer')
                        <tr>
                            <th wire:click="sortBy('contact_id')" style="cursor: pointer;" @if($sortField==='contact_id' ) class="{{ $sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc' }}" @else class="sorting" @endif>
                                Contact ID
                            </th>
                            <th wire:click="sortBy('name')" style="cursor: pointer;" @if($sortField==='name' ) class="{{ $sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc' }}" @else class="sorting" @endif>
                                Name
                            </th>
                            <th wire:click="sortBy('customer_group')" style="cursor: pointer;" @if($sortField==='customer_group' ) class="{{ $sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc' }}" @else class="sorting" @endif>
                                Customer Group
                            </th>
                            <th wire:click="sortBy('mobile')" style="cursor: pointer;" @if($sortField==='mobile' ) class="{{ $sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc' }}" @else class="sorting" @endif>
                                Address
                            </th>
                            <th wire:click="sortBy('mobile')" style="cursor: pointer;" @if($sortField==='mobile' ) class="{{ $sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc' }}" @else class="sorting" @endif>
                                Contact
                            </th>
                            <th wire:click="sortBy('points_value')" style="cursor: pointer;" @if($sortField==='points_value' ) class="{{ $sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc' }}" @else class="sorting" @endif>
                                Total Points
                            </th>
                            <th wire:click="sortBy('points_status')" style="cursor: pointer;" @if($sortField==='points_status' ) class="{{ $sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc' }}" @else class="sorting" @endif>
                                Can Gain Points
                            </th>
                            <th class="text-center">
                                Total Sales Due
                            </th>
                            <th class="text-center">
                                Total Sales Return Due
                            </th>
                            <th class="text-center">
                                Actions
                            </th>


                        </tr>
                        @endif
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
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
                        @elseif($type == 'customer')
                        <tr>
                            <td>{{$contact->contact_id}}</td>
                            <td>{{$contact->name}}</td>
                            <td>{{$contact->customer_group}}</td>
                            <td>{{implode(array_filter([$contact->landmark, $contact->city, $contact->state, $contact->country]))}}</td>
                            <td>{{$contact->mobile}}</td>
                            <td>{{$contact->points_value}}</td>
                            <td>{{$contact->points_status ? 'Yes' : 'No'}}</td>
                            <td class="display_currency" data-currency_symbol="true">{{$contact->total_invoice - $contact->invoice_received}}</td>
                            <td class="display_currency" data-currency_symbol="true">{{$contact->total_sell_return - $contact->sell_return_paid}}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info dropdown-toggle btn-xs"
                                        data-toggle="dropdown" aria-expanded="false">@lang("messages.actions")<span class="caret"></span><span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        @if(($contact->total_invoice + $contact->opening_balance - $contact->total_invoice_received - $contact->opening_balance_paid) > 0)
                                        <li><a href="{{route('payments.getPayContactDue', [$contact->id])}}?type=sell" class="pay_sale_due"><i class="fa fa-money" aria-hidden="true"></i>@lang("contact.pay_due_amount")</a></li>
                                        @endif
                                        @if(($contact->total_sell_return - $contact->sell_return_paid) > 0)
                                        <li><a href="{{route('payments.getPayContactDue', [$contact->id])}}?type=sell_return" class="pay_purchase_due"><i class="fa fa-money" aria-hidden="true"></i>@lang("lang_v1.pay_sell_return_due")</a></li>
                                        @endif
                                        @can("customer.view")
                                        <li><a href="{{route('contacts.show', [$contact->id])}}"><i class="fa fa-external-link" aria-hidden="true"></i> @lang("messages.view")</a></li>
                                        @endcan
                                        @if($contact->id != 1 && $contact->id != 2)
                                        @can("customer.update")
                                        <li><a href="{{route('contacts.edit', [$contact->id])}}" class="edit_contact_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a></li>
                                        @endcan
                                        @endif
                                        @if($contact->id != 1 && $contact->id != 2 && !$contact->is_default)
                                        @can("customer.delete")
                                        <li><a href="#" wire:click.prevent="deleteContact({{ $contact->id }})" class="delete_contact_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</a></li>
                                        @endcan
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="{{ $type == 'supplier' ? 7 : 10 }}" class="text-center">No Data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            @if($type == 'supplier')
                            <th colspan="4" class="text-right">@lang('sale.total')</th>
                            @else
                            <th colspan="7" class="text-right">@lang('sale.total')</th>
                            @endif
                            <td class="text-right">
                                <span class="display_currency" data-currency_symbol="true" id="footer_contact_due">{{ $totalDue }}</span>
                            </td>
                            <td class="text-right">
                                <span class="display_currency" data-currency_symbol="true" id="footer_contact_return_due">{{ $totalReturnDue }}</span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @include('includes.pagination', ['paginator' => $contacts])

        </div>
        @endif
        @endcomponent

        <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>
        <div class="modal fade pay_contact_due_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->

    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('contactDeleted', message => {
                toastr.success(message);
            });

            // Initialize currency formatting
            __currency_convert_recursively($('#contact_table'));
        });
    </script>
    @endpush
</div>
