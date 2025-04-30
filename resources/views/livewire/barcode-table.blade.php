<div>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('barcode.barcodes')
            <small>@lang('barcode.manage_your_barcodes')</small>
        </h1>
        <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('barcode.all_your_barcode')])
        @slot('tool')
        <div class="box-tools">
            <a class="btn btn-block btn-primary" href="{{route('barcodes.create')}}">
                <i class="fa fa-plus"></i> @lang('barcode.add_new_setting')</a>
        </div>
        @endslot
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>@lang('barcode.setting_name')</th>
                        <th>@lang('barcode.setting_description')</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barcodes as $barcode)
                    <tr>
                        <td>
                            {{ $barcode->name }}
                            @if($barcode->is_default)
                            <span class="label label-success">{{ __('barcode.default') }}</span>
                            @endif
                        </td>
                        <td>{{ $barcode->description }}</td>
                        <td>
                            <a href="{{ route('barcodes.edit', [$barcode->id]) }}" class="btn btn-xs btn-primary">
                                <i class="glyphicon glyphicon-edit"></i> @lang('messages.edit')
                            </a>

                            <button
                                wire:click="deleteBarcode({{ $barcode->id }})"
                                class="btn btn-xs btn-danger"
                                @if($barcode->is_default) disabled @endif
                                >
                                <i class="glyphicon glyphicon-trash"></i> @lang('messages.delete')
                            </button>



                            @if($barcode->is_default)
                            <button class="btn btn-xs btn-success" disabled>
                                <i class="fa fa-check-square-o" aria-hidden="true"></i> @lang('barcode.default')
                            </button>
                            @else
                            <button wire:click="setDefault({{ $barcode->id }})" class="btn btn-xs btn-info">
                                @lang('barcode.set_as_default')
                            </button>
                            @endif
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
