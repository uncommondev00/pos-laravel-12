<div>
    {{-- Success is as dangerous as failure. --}}

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang( 'user.roles' )
            <small>@lang( 'user.manage_roles' )</small>
        </h1>
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol> -->
    </section>

    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __( 'user.all_roles' )])
            @can('roles.create')
                @slot('tool')
                    <div class="box-tools">
                        <a class="btn btn-block btn-primary" 
                        href="{{route('roles.create')}}" >
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</a>
                    </div>
                @endslot
            @endcan
            @can('roles.view')
            <div class="table-responsive">
                @include('includes.table-controls', [
                       'perPageOptions' => $perPageOptions,
                       'search' => $search
                   ])
                <table class="table table-bordered table-striped">
                    
                    <thead>
                        <tr>
                            <th>@lang( 'user.roles' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @can('roles.update')
                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-xs btn-primary">
                                                <i class="fa fa-edit"></i> @lang("messages.edit")
                                            </a>
                                        @endcan
                                        @can('roles.delete')
                                            <button wire:click="delete({{ $role->id }})" class="btn btn-xs btn-danger">
                                                <i class="glyphicon glyphicon-trash"></i> Delete
                                            </button>
                                            {{-- <button class="btn btn-xs btn-danger" onclick="confirmDelete({{ $user->id }})">
                                                <i class="glyphicon glyphicon-trash"></i> Delete
                                            </button> --}}
                                        @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No Data</td>
                            </tr>
                        @endforelse
                </table>

               

            </div>

            @include('includes.pagination', ['paginator' => $roles])

            @endcan
        @endcomponent
    
    </section>
</div>
