<div>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('printer.printers')
            <small>@lang('printer.manage_your_printers')</small>
        </h1>
        <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('printer.all_your_printer')])
        @slot('tool')
        <div class="box-tools">
            <a class="btn btn-block btn-primary" href="{{route('printers.create')}}">
                <i class="fa fa-plus"></i> @lang('printer.add_printer')</a>
        </div>
        @endslot
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>@lang('printer.name')</th>
                        <th>@lang('printer.connection_type')</th>
                        <th>@lang('printer.capability_profile')</th>
                        <th>@lang('printer.character_per_line')</th>
                        <th>@lang('printer.ip_address')</th>
                        <th>@lang('printer.port')</th>
                        <th>@lang('printer.path')</th>
                        <th>@lang('messages.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($printers as $printer)
                    <tr>
                        <td>{{$printer->name}}</td>
                        <td>{{Printer::connection_type_str($printer->connection_type)}}</td>
                        <td>{{Printer::capability_profile_str($printer->capability_profile)}}</td>
                        <td>{{$printer->character_per_line}}</td>
                        <td>{{$printer->ip_address}}</td>
                        <td>{{$printer->port}}</td>
                        <td>{{$printer->path}}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm" wire:click="edit({{$printer->id}})" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-default btn-sm" wire:click="delete({{$printer->id}})"><i class="fa fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endcomponent

    </section>
    <!-- /.content -->
</div>
