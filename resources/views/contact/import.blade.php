@extends('layouts.app')
@section('title', __('lang_v1.import_contacts'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('lang_v1.import_contacts')
    </h1>
</section>

<!-- Main content -->
<section class="content">

    @if (session('notification') || !empty($notification))
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                @if(!empty($notification['msg']))
                {{$notification['msg']}}
                @elseif(session('notification.msg'))
                {{ session('notification.msg') }}
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            @component('components.widget', ['class' => 'box-primary'])
            <form action="{{ route('contacts.postImportContacts') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="contacts_csv">{{ __('product.file_to_import') }}:</label>
                                <input type="file" name="contacts_csv" accept=".csv" required="required">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <br>
                            <button type="submit" class="btn btn-primary">@lang('messages.submit')</button>
                        </div>
                    </div>
                </div>
            </form>

            <br><br>
            <div class="row">
                <div class="col-sm-4">
                    <a href="{{ asset('uploads/files/import_contacts_csv_template.csv') }}" class="btn btn-success" download><i class="fa fa-download"></i> @lang('product.download_csv_file_template')</a>
                </div>
            </div>
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.instructions')])
            <strong>@lang('lang_v1.instruction_line1')</strong><br>
            @lang('lang_v1.instruction_line2')
            <br><br>
            <table class="table table-striped">
                <tr>
                    <th>@lang('lang_v1.col_no')</th>
                    <th>@lang('lang_v1.col_name')</th>
                    <th>@lang('lang_v1.instruction')</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>@lang('contact.contact_type') <small class="text-muted">(@lang('lang_v1.required'))</small></td>
                    <td>{!! __('lang_v1.contact_type_ins') !!}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>@lang('contact.name') <small class="text-muted">(@lang('lang_v1.required'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>@lang('business.business_name') <br><small class="text-muted">(@lang('lang_v1.required_if_supplier'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>@lang('lang_v1.contact_id') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>@lang('lang_v1.contact_id_ins')</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>@lang('contact.tax_no') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>@lang('lang_v1.opening_balance') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>@lang('contact.pay_term') <br><small class="text-muted">(@lang('lang_v1.required_if_supplier'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>@lang('contact.pay_term_period') <br><small class="text-muted">(@lang('lang_v1.required_if_supplier'))</small></td>
                    <td><strong>@lang('lang_v1.pay_term_period_ins')</strong></td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>@lang('lang_v1.credit_limit') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>@lang('business.email') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>11</td>
                    <td>@lang('contact.mobile') <small class="text-muted">(@lang('lang_v1.required'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>12</td>
                    <td>@lang('contact.alternate_contact_number') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>13</td>
                    <td>@lang('contact.landline') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>14</td>
                    <td>@lang('business.city') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>15</td>
                    <td>@lang('business.state') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>16</td>
                    <td>@lang('business.country') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>17</td>
                    <td>@lang('business.landmark') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>18</td>
                    <td>@lang('lang_v1.custom_field', ['number' => 1]) <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>19</td>
                    <td>@lang('lang_v1.custom_field', ['number' => 2]) <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>20</td>
                    <td>@lang('lang_v1.custom_field', ['number' => 3]) <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>21</td>
                    <td>@lang('lang_v1.custom_field', ['number' => 4]) <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            @endcomponent
        </div>
    </div>
</section>
<!-- /.content -->

@endsection
