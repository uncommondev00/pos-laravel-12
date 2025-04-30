<div>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang( 'invoice.invoice_settings' )
            <small>@lang( 'invoice.manage_your_invoices' )</small>
        </h1>
        <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">@lang('invoice.invoice_schemes')</a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">@lang('invoice.invoice_layouts')</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>@lang( 'invoice.all_your_invoice_schemes' ) <button type="button" class="btn btn-primary btn-modal pull-right"
                                            data-href="{{route('invoice-schemes.create')}}"
                                            data-container=".invoice_modal">
                                            <i class="fa fa-plus"></i> @lang( 'messages.add' )</button></h4>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>@lang( 'invoice.name' ) @show_tooltip(__('tooltip.invoice_scheme_name'))</th>
                                                    <th>@lang( 'invoice.prefix' ) @show_tooltip(__('tooltip.invoice_scheme_prefix'))</th>
                                                    <th>@lang( 'invoice.start_number' ) @show_tooltip(__('tooltip.invoice_scheme_start_number'))</th>
                                                    <th>@lang( 'invoice.invoice_count' ) @show_tooltip(__('tooltip.invoice_scheme_count'))</th>
                                                    <th>@lang( 'invoice.total_digits' ) @show_tooltip(__('tooltip.invoice_scheme_total_digits'))</th>
                                                    <th>@lang( 'messages.action' )</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($schemes as $scheme)
                                                <tr>
                                                    <td>
                                                        {{ $scheme->name }}
                                                        @if ($scheme->is_default)
                                                        <span class="label label-success">@lang('barcode.default')</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $scheme->scheme_type === 'year' ? date('Y') . '-' : $scheme->prefix }}
                                                    </td>
                                                    <td>{{ $scheme->start_number }}</td>
                                                    <td>{{ $scheme->invoice_count }}</td>
                                                    <td>{{ $scheme->total_digits }}</td>
                                                    <td>

                                                        <button type="button"
                                                            data-href="{{ route('invoice-schemes.edit', [$scheme->id]) }}"
                                                            class="btn btn-xs btn-primary btn-modal"
                                                            data-container=".invoice_edit_modal">
                                                            <i class="glyphicon glyphicon-edit"></i> @lang('messages.edit')
                                                        </button>


                                                        <button type="button"
                                                            data-href="{{ route('invoice-schemes.destroy', [$scheme->id]) }}"
                                                            class="btn btn-xs btn-danger delete_invoice_button"
                                                            @if($scheme->is_default) disabled @endif>
                                                            <i class="glyphicon glyphicon-trash"></i> @lang('messages.delete')
                                                        </button>


                                                        @if($scheme->is_default)
                                                        <button type="button" class="btn btn-xs btn-success" disabled>
                                                            <i class="fa fa-check-square-o" aria-hidden="true"></i> @lang('barcode.default')
                                                        </button>
                                                        @else
                                                        <button wire:click="setDefault({{ $scheme->id }})" class="btn btn-xs btn-info">
                                                            @lang('barcode.set_as_default')
                                                        </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>@lang( 'invoice.all_your_invoice_layouts' ) <a class="btn btn-primary pull-right" href="{{route('invoice-layouts.create')}}">
                                            <i class="fa fa-plus"></i> @lang( 'messages.add' )</a></h4>
                                </div>
                                <div class="col-md-12">
                                    @foreach( $invoiceLayouts as $layout)
                                    <div class="col-md-3">
                                        <div class="icon-link">
                                            <a href="{{route('invoice-layouts.edit', [$layout->id])}}">
                                                <i class="fa fa-file-text-o fa-4x"></i>
                                                {{ $layout->name }}
                                            </a>
                                            @if( $layout->is_default )
                                            <span class="badge bg-green">@lang("barcode.default")</span>
                                            @endif
                                            @if($layout->locations->count())
                                            <span class="link-des">
                                                <b>@lang('invoice.used_in_locations'): </b><br>
                                                @foreach($layout->locations as $location)
                                                {{ $location->name }}
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                                &nbsp;
                                                @endforeach
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if( $loop->iteration % 4 == 0 )
                                    <div class="clearfix"></div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            <br>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
            </div>
        </div>

        <div class="modal fade invoice_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel">
        </div>
        <div class="modal fade invoice_edit_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
</div>
