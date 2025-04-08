<!-- Fix for scroll issue in new booking -->
<style type="text/css">
  .modal {
    overflow-y:auto; 
  }
</style>
<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('notification.send') }}" method="POST" id="send_notification_form">
      @csrf

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">@lang('lang_v1.send_notification') - {{ $template_name }}</h4>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label class="radio-inline">
            <input type="radio" name="notification_type" value="email_only" class="input-icheck" checked id="notification_type_email_only">
            @lang('lang_v1.send_email_only')
          </label>
          <label class="radio-inline">
            <input type="radio" name="notification_type" value="sms_only" class="input-icheck" id="notification_type_sms_only">
            @lang('lang_v1.send_sms_only')
          </label>
          <label class="radio-inline">
            <input type="radio" name="notification_type" value="both" class="input-icheck" id="notification_type_both">
            @lang('lang_v1.send_both_email_n_sms')
          </label>
        </div>

        <div id="email_div">
          <div class="form-group">
            <label for="to_email">@lang('lang_v1.to'):</label>
            <input type="email" name="to_email" id="to_email" value="{{ $transaction->contact->email }}" class="form-control" placeholder="@lang('lang_v1.to')">
          </div>
          <div class="form-group">
            <label for="subject">@lang('lang_v1.email_subject'):</label>
            <input type="text" name="subject" id="subject" value="{{ $notification_template['subject'] }}" class="form-control" placeholder="@lang('lang_v1.email_subject')">
          </div>
          <div class="form-group">
            <label for="email_body">@lang('lang_v1.email_body'):</label>
            <textarea name="email_body" id="email_body" class="form-control" rows="6" placeholder="@lang('lang_v1.email_body')">{{ $notification_template['email_body'] }}</textarea>
          </div>
        </div>

        <div id="sms_div" class="hide">
          <div class="form-group">
            <label for="mobile_number">@lang('lang_v1.mobile_number'):</label>
            <input type="text" name="mobile_number" id="mobile_number" value="{{ $transaction->contact->mobile }}" class="form-control" placeholder="@lang('lang_v1.mobile_number')">
          </div>
          <div class="form-group">
            <label for="sms_body">@lang('lang_v1.sms_body'):</label>
            <textarea name="sms_body" id="sms_body" class="form-control" rows="6" placeholder="@lang('lang_v1.sms_body')">{{ $notification_template['sms_body'] }}</textarea>
          </div>
        </div>

        <strong>@lang('lang_v1.available_tags'):</strong>
        <p class="help-block">{{ implode(', ', $tags) }}</p>

        <input type="hidden" name="transaction_id" value="{{ $transaction->id }}" id="transaction_id">
        <input type="hidden" name="template_for" value="{{ $notification_template['template_for'] }}" id="template_for">
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="send_notification_btn">@lang('lang_v1.send')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
      </div>
    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script type="text/javascript">
// Fix for not updating textarea value on modal
  CKEDITOR.on('instanceReady', function(){
     $.each( CKEDITOR.instances, function(instance) {
      CKEDITOR.instances[instance].on("change", function(e) {
          for ( instance in CKEDITOR.instances )
          CKEDITOR.instances[instance].updateElement();
      });
     });
  });
  $(document).ready(function(){
    CKEDITOR.replace('email_body');

    //initialize iCheck
    $('input[type="checkbox"].input-icheck, input[type="radio"].input-icheck').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue'
    });
  });

  $(document).on('ifChanged', 'input[type=radio][name=notification_type]', function(){
    var notification_type = $(this).val();
    if (notification_type == 'email_only') {
      $('div#email_div').removeClass('hide');
      $('div#sms_div').addClass('hide');
    } else if(notification_type == 'sms_only'){
      $('div#email_div').addClass('hide');
      $('div#sms_div').removeClass('hide');
    } else if(notification_type == 'both'){
      $('div#email_div').removeClass('hide');
      $('div#sms_div').removeClass('hide');
    }
  });
  $('#send_notification_form').submit(function(e){
    e.preventDefault();
    var data = $(this).serialize();
    $('#send_notification_btn').text("@lang('lang_v1.sending')...");
    $('#send_notification_btn').attr('disabled', 'disabled');
    $.ajax({
      method: "POST",
      url: $(this).attr("action"),
      dataType: "json",
      data: data,
      success: function(result){
        if(result.success == true){
          $('div.view_modal').modal('hide');
          toastr.success(result.msg);
        } else {
          toastr.error(result.msg);
        }
        $('#send_notification_btn').text("@lang('lang_v1.send')");
        $('#send_notification_btn').removeAttr('disabled');
      }
    });
  });
</script>