<div class="modal-dialog modal-lg" role="document" >
  <div class="modal-content">
  @php
    $form_id = 'contact_add_form';
    if(isset($quick_add)){
    $form_id = 'quick_add_contact';
    }
  @endphp
    <form action="{{ route('contacts.store') }}" method="POST" id="{{ $form_id }}">
    @csrf

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang('contact.add_contact')</h4>
    </div>

    <div class="modal-body">
      <div class="row">

      <div class="col-md-6 contact_type_div">
        <div class="form-group">
            <label for="type">{{ __('contact.contact_type') }}:*</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <select name="type" id="contact_type" class="form-control" required>
                    <option value="">{{ __('messages.please_select') }}</option>
                    @foreach($types as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{ __('contact.name') }}:*</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" name="name" class="form-control mousetrap" placeholder="{{ __('contact.name') }}" required autofocus>
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="col-md-4 supplier_fields">
        <div class="form-group">
            <label for="supplier_business_name">{{ __('business.business_name') }}:*</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
                <input type="text" name="supplier_business_name" class="form-control" required placeholder="{{ __('business.business_name') }}">
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group">
            <label for="contact_id">{{ __('lang_v1.contact_id') }}:</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-id-badge"></i></span>
                <input type="text" name="contact_id" class="form-control" placeholder="{{ __('lang_v1.contact_id') }}">
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group">
            <label for="tax_number">{{ __('contact.tax_no') }}:</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" name="tax_number" class="form-control" placeholder="{{ __('contact.tax_no') }}">
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="col-md-4">
        <div class="form-group">
            <label for="opening_balance">{{ __('lang_v1.opening_balance') }}:</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                <input type="text" name="opening_balance" class="form-control input_number" value="0">
            </div>
        </div>
    </div>
</div>


        <!--next-->

        <div class="col-md-4">
    <div class="form-group">
        <div class="multi-input">
            <label for="pay_term_number">{{ __('contact.pay_term') }}:</label> 
            @show_tooltip(__('tooltip.pay_term'))
            <br/>
            <input type="number" name="pay_term_number" id="pay_term_number" class="form-control width-40 pull-left" placeholder="{{ __('contact.pay_term') }}">
            
            <select name="pay_term_type" class="form-control width-60 pull-left">
                <option value="">{{ __('messages.please_select') }}</option>
                <option value="months">{{ __('lang_v1.months') }}</option>
                <option value="days">{{ __('lang_v1.days') }}</option>
            </select>
        </div>
    </div>
</div>

<div class="col-md-4 customer_fields">
    <div class="form-group">
        <label for="customer_group_id">{{ __('lang_v1.customer_group') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-users"></i>
            </span>
            <select name="customer_group_id" id="customer_group_id" class="form-control">
                @foreach($customer_groups as $key => $group)
                    <option value="{{ $key }}">{{ $group }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-md-4 customer_fields">
    <div class="form-group">
        <label for="credit_limit">{{ __('lang_v1.credit_limit') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-money"></i>
            </span>
            <input type="text" name="credit_limit" id="credit_limit" class="form-control input_number">
        </div>
        <p class="help-block">@lang('lang_v1.credit_limit_help')</p>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="points_status" value="1"> {{ __('Allow to gain points') }}
            </label>
            @show_tooltip(__('Check if customer can gain points'))
        </div>
    </div>
</div>


        <!--next-->

      <div class="col-md-12">
        <hr/>
      </div>
      <div class="col-md-3">
    <div class="form-group">
        <label for="email">{{ __('business.email') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-envelope"></i>
            </span>
            <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('business.email') }}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="mobile">{{ __('contact.mobile') }}:*</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-mobile"></i>
            </span>
            <input type="text" name="mobile" id="mobile" class="form-control" required placeholder="{{ __('contact.mobile') }}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="alternate_number">{{ __('contact.alternate_contact_number') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-phone"></i>
            </span>
            <input type="text" name="alternate_number" id="alternate_number" class="form-control" placeholder="{{ __('contact.alternate_contact_number') }}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="landline">{{ __('contact.landline') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-phone"></i>
            </span>
            <input type="text" name="landline" id="landline" class="form-control" placeholder="{{ __('contact.landline') }}">
        </div>
    </div>
</div>
<!--next-->
      <div class="clearfix"></div>

      <div class="col-md-3">
    <div class="form-group">
        <label for="city">{{ __('business.city') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
            </span>
            <input type="text" name="city" id="city" class="form-control" placeholder="{{ __('business.city') }}">
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
            <input type="text" name="state" id="state" class="form-control" placeholder="{{ __('business.state') }}">
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
            <input type="text" name="country" id="country" class="form-control" placeholder="{{ __('business.country') }}">
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
            <input type="text" name="landmark" id="landmark" class="form-control" placeholder="{{ __('business.landmark') }}">
        </div>
    </div>
</div>


      
      <div class="@if(isset($quick_add)) hide @endif"> 
      <div class="clearfix"></div>
      <div class="col-md-12">
        <hr/>
      </div>
     <div class="col-md-3">
    <div class="form-group">
        <label for="custom_field1">{{ __('lang_v1.custom_field', ['number' => 1]) }}:</label>
        <input type="text" name="custom_field1" id="custom_field1" class="form-control" placeholder="{{ __('lang_v1.custom_field', ['number' => 1]) }}">
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="custom_field2">{{ __('lang_v1.custom_field', ['number' => 2]) }}:</label>
        <input type="text" name="custom_field2" id="custom_field2" class="form-control" placeholder="{{ __('lang_v1.custom_field', ['number' => 2]) }}">
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="custom_field3">{{ __('lang_v1.custom_field', ['number' => 3]) }}:</label>
        <input type="text" name="custom_field3" id="custom_field3" class="form-control" placeholder="{{ __('lang_v1.custom_field', ['number' => 3]) }}">
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="custom_field4">{{ __('lang_v1.custom_field', ['number' => 4]) }}:</label>
        <input type="text" name="custom_field4" id="custom_field4" class="form-control" placeholder="{{ __('lang_v1.custom_field', ['number' => 4]) }}">
    </div>
</div>

      </div>
      <div class="clearfix"></div>

    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary" id="customer_save">@lang( 'messages.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

   </form>
  
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->