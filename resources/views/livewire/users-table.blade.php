<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
     <!-- Content Header -->
     <section class="content-header">
        <h1>@lang( 'user.users' )
            <small>@lang( 'user.manage_users' )</small>
        </h1>
    </section>

    <!-- Main Content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __( 'user.all_users' )])
            @can('user.create')
                @slot('tool')
                    <div class="box-tools">
                        <a class="btn btn-block btn-primary" href="{{ route('users.create') }}">
                            <i class="fa fa-plus"></i> @lang( 'messages.add' )
                        </a>
                    </div>
                @endslot
            @endcan

            @can('user.view')
                <div class="table-responsive">
                   @include('includes.table-controls', [
                       'perPageOptions' => $perPageOptions,
                       'search' => $search
                   ])
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="cursor-pointer">
                                    @lang( 'business.username' )
                                </th>
                                <th>@lang( 'user.name' )</th>
                                <th>@lang( 'user.role' )</th>
                                <th>@lang( 'business.email' )</th>
                                <th>@lang( 'messages.action' )</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr wire:loading.class.delay="opacity-50">
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->surname . ' ' . $user->first_name . ' ' . $user->last_name }}</td>
                                    <td>{{ optional($user->getRoleNames())[0] }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @can('user.update')
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-xs btn-primary">
                                                <i class="fa fa-edit"></i> @lang("messages.edit")
                                            </a>
                                        @endcan
                                        @can('user.delete')
                                            <button wire:click="delete({{ $user->id }})" class="btn btn-xs btn-danger">
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
                                    <td colspan="5" class="text-center">No Data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    
                </div>
                @include('includes.pagination', ['paginator' => $users])
            @endcan
        @endcomponent
    </section>

</div>
