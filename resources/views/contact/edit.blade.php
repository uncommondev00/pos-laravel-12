<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        <form action="{{ route('contacts.update', [$contact->id]) }}" method="POST" id="contact_edit_form">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('contact.edit_contact')</h4>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type">@lang('contact.contact_type'):* </label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <select name="type" id="contact_type" class="form-control" required>
                                    <option value="">@lang('messages.please_select')</option>
                                    @foreach ($types as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ $contact->type == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">@lang('contact.name'):* </label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="@lang('contact.name')" value="{{ $contact->name }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-4 supplier_fields">
                        <div class="form-group">
                            <label for="supplier_business_name">@lang('business.business_name'):* </label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </span>
                                <input type="text" name="supplier_business_name" class="form-control"
                                    placeholder="@lang('business.business_name')" value="{{ $contact->supplier_business_name }}"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contact_id">@lang('lang_v1.contact_id'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-id-badge"></i>
                                </span>
                                <input type="hidden" id="hidden_id" value="{{ $contact->id }}">
                                <input type="text" name="contact_id" class="form-control"
                                    placeholder="@lang('lang_v1.contact_id')" value="{{ $contact->contact_id }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tax_number">@lang('contact.tax_no'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                <input type="text" name="tax_number" class="form-control"
                                    placeholder="@lang('contact.tax_no')" value="{{ $contact->tax_number }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="opening_balance">@lang('lang_v1.opening_balance'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-money"></i>
                                </span>
                                <input type="text" name="opening_balance" class="form-control input_number"
                                    value="{{ $opening_balance }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 customer_fields">
                        <div class="form-group">
                            <label for="customer_group_id">@lang('lang_v1.customer_group'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-users"></i>
                                </span>
                                <select name="customer_group_id" class="form-control">
                                    @foreach ($customer_groups as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ $contact->customer_group_id == $key ? 'selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">@lang('business.email'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control"
                                    placeholder="@lang('business.email')" value="{{ $contact->email }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="mobile">@lang('contact.mobile'):* </label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-mobile"></i>
                                </span>
                                <input type="text" name="mobile" class="form-control"
                                    placeholder="@lang('contact.mobile')" value="{{ $contact->mobile }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="alternate_number">@lang('contact.alternate_contact_number'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-phone"></i>
                                </span>
                                <input type="text" name="alternate_number" class="form-control"
                                    placeholder="@lang('contact.alternate_contact_number')" value="{{ $contact->alternate_number }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="landline">@lang('contact.landline'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-phone"></i>
                                </span>
                                <input type="text" name="landline" class="form-control"
                                    placeholder="@lang('contact.landline')" value="{{ $contact->landline }}">
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="city">{{ __('business.city') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                <input type="text" name="city" id="city" class="form-control"
                                    placeholder="{{ __('business.city') }}"
                                    value="{{ old('city', $contact->city) }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="state">{{ __('business.state') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                <input type="text" name="state" id="state" class="form-control"
                                    placeholder="{{ __('business.state') }}"
                                    value="{{ old('state', $contact->state) }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="country">{{ __('business.country') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-globe"></i>
                                </span>
                                <input type="text" name="country" id="country" class="form-control"
                                    placeholder="{{ __('business.country') }}"
                                    value="{{ old('country', $contact->country) }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="landmark">{{ __('business.landmark') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                <input type="text" name="landmark" id="landmark" class="form-control"
                                    placeholder="{{ __('business.landmark') }}"
                                    value="{{ old('landmark', $contact->landmark) }}">
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-12">
                        <hr />
                    </div>

                    @for ($i = 1; $i <= 4; $i++)
                        <div class="col-md-3">
                            <div class="form-group">
                                <label
                                    for="custom_field{{ $i }}">{{ __('lang_v1.custom_field', ['number' => $i]) }}:</label>
                                <input type="text" name="custom_field{{ $i }}"
                                    id="custom_field{{ $i }}" class="form-control"
                                    placeholder="{{ __('lang_v1.custom_field', ['number' => $i]) }}"
                                    value="{{ old('custom_field' . $i, $contact->{'custom_field' . $i}) }}">
                            </div>
                        </div>
                    @endfor

                    <div class="clearfix"></div>


                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>

        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
