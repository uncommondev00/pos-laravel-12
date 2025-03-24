@extends('layouts.app')
@section('title', __('role.add_role'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('role.add_role')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary'])
            <form action="{{ route('roles.store') }}" method="POST" id="role_add_form">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">{{ __('user.role_name') }}:*</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                placeholder="{{ __('user.role_name') }}">
                        </div>
                    </div>
                </div>

                @if (in_array('service_staff', $enabled_modules))
                    <div class="row">
                        <div class="col-md-2">
                            <h4>@lang('lang_v1.user_type')</h4>
                        </div>
                        <div class="col-md-9 col-md-offset-1">
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_service_staff" value="1" class="input-icheck">
                                        {{ __('restaurant.service_staff') }}
                                    </label>
                                    @show_tooltip(__('restaurant.tooltip_service_staff'))
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-3">
                        <label>@lang('user.permissions'):</label>
                    </div>
                </div>

                @foreach (['user', 'roles', 'supplier', 'customer', 'brand', 'tax_rate', 'unit', 'category'] as $section)
                    <div class="row check_group">
                        <div class="col-md-1">
                            @if ($section == 'roles')
                                <h4>@lang('user.roles')</h4>
                            @elseif($section == 'category')
                                <h4>@lang('category.category')</h4>
                            @else
                                <h4>@lang('role.' . $section)</h4>
                            @endif

                        </div>
                        <div class="col-md-2">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            @foreach (['view', 'create', 'update', 'delete'] as $permission)
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <label>
                                            @if ($section == 'roles' && $permission == 'view')
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $section . '.' . $permission }}" class="input-icheck">
                                                {{ __('lang_v1.view_role') }}
                                            @elseif ($section == 'roles' && $permission == 'create')
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $section . '.' . $permission }}" class="input-icheck">
                                                {{ __('role.add_role') }}
                                            @elseif ($section == 'roles' && $permission == 'update')
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $section . '.' . $permission }}" class="input-icheck">
                                                {{ __('role.edit_role') }}
                                            @elseif ($section == 'roles' && $permission == 'delete')
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $section . '.' . $permission }}" class="input-icheck">
                                                {{ __('lang_v1.delete_role') }}
                                            @else
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $section . '.' . $permission }}" class="input-icheck">
                                                {{ __('role.' . $section . '.' . $permission) }}
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                @endforeach
                <!--next-->
                @php
                    $modules = [
                        'product' => [
                            'product.view' => 'role.product.view',
                            'product.create' => 'role.product.create',
                            'product.update' => 'role.product.update',
                            'product.delete' => 'role.product.delete',
                            'product.opening_stock' => 'lang_v1.add_opening_stock',
                        ],
                        'purchase' => [
                            'purchase.view' => 'role.purchase.view',
                            'purchase.create' => 'role.purchase.create',
                            'purchase.update' => 'role.purchase.update',
                            'purchase.delete' => 'role.purchase.delete',
                            'purchase.payments' => 'lang_v1.purchase.payments',
                        ],
                        'sell' => [
                            'sell.view' => 'role.sell.view',
                            'sell.create' => 'role.sell.create',
                            'sell.update' => 'role.sell.update',
                            'sell.delete' => 'role.sell.delete',
                            'direct_sell.access' => 'role.direct_sell.access',
                            'sell.payments' => 'lang_v1.sell.payments',
                            'edit_product_price_from_sale_screen' => 'lang_v1.edit_product_price_from_sale_screen',
                            'edit_product_discount_from_sale_screen' =>
                                'lang_v1.edit_product_discount_from_sale_screen',
                            'discount.access' => 'lang_v1.discount.access',
                        ],
                    ];
                @endphp

                @foreach ($modules as $module => $permissions)
                    <div class="row check_group">
                        <div class="col-md-1">
                            @if ($module == 'product')
                                <h4>@lang('business.product')</h4>
                            @elseif($module == 'sell')
                                <h4>@lang('sale.sale')</h4>
                            @else
                                <h4>@lang('role.' . $module)</h4>
                            @endif

                        </div>
                        <div class="col-md-2">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="check_all input-icheck">
                                    {{ __('role.select_all') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            @foreach ($permissions as $permission => $label)
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="permissions[]" value="{{ $permission }}"
                                                class="input-icheck">
                                            {{ __($label) }}
                                        </label>
                                        @if ($permission === 'purchase.payments' || $permission === 'sell.payments')
                                            @show_tooltip(__($label))
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                @endforeach

                <!--Next-->
                <div class="row check_group">
                    <div class="col-md-1">
                        <h4>@lang('role.report')</h4>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9">
                        @php
                            $reports = [
                                'purchase_n_sell_report.view',
                                'tax_report.view',
                                'contacts_report.view',
                                'expense_report.view',
                                'profit_loss_report.view',
                                'stock_report.view',
                                'trending_product_report.view',
                                'register_report.view',
                                'sales_representative.view',
                            ];
                        @endphp
                        @foreach ($reports as $report)
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="permissions[]" value="{{ $report }}"
                                            class="input-icheck">
                                        {{ __('role.' . $report) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <hr>
                <div class="row check_group">
                    <div class="col-md-1">
                        <h4>@lang('role.settings')</h4>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9">
                        @php
                            $settings = [
                                'business_settings.access',
                                'barcode_settings.access',
                                'invoice_settings.access',
                                'expense.access',
                            ];
                        @endphp
                        @foreach ($settings as $setting)
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="permissions[]" value="{{ $setting }}"
                                            class="input-icheck">
                                        {{ __('role.' . $setting) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <hr>
                <div class="row check_group">
                    <div class="col-md-3">
                        <h4>@lang('role.dashboard') @show_tooltip(__('tooltip.dashboard_permission'))</h4>
                    </div>
                    <div class="col-md-9">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="permissions[]" value="dashboard.data" class="input-icheck"
                                        checked>
                                    {{ __('role.dashboard.data') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row check_group">
                    <div class="col-md-3">
                        <h4>@lang('account.account')</h4>
                    </div>
                    <div class="col-md-9">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="permissions[]" value="account.access" class="input-icheck">
                                    {{ __('lang_v1.access_accounts') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                @if (in_array('tables', $enabled_modules) && in_array('service_staff', $enabled_modules))
                    <div class="row check_group">
                        <div class="col-md-1">
                            <h4>@lang('restaurant.bookings')</h4>
                        </div>
                        <div class="col-md-2">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            @php
                                $bookings = [
                                    'crud_all_bookings' => 'restaurant.add_edit_view_all_booking',
                                    'crud_own_bookings' => 'restaurant.add_edit_view_own_booking',
                                ];
                            @endphp
                            @foreach ($bookings as $key => $booking)
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                                class="input-icheck">
                                            {{ __($booking) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                @endif
                <div class="row">
                    <div class="col-md-3">
                        <h4>@lang('role.access_locations') @show_tooltip(__('tooltip.access_locations_permission'))</h4>
                    </div>
                    <div class="col-md-9">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="permissions[]" value="access_all_locations"
                                        class="input-icheck" checked>
                                    {{ __('role.all_locations') }}
                                </label>
                                @show_tooltip(__('tooltip.all_location_permission'))
                            </div>
                        </div>
                        @foreach ($locations as $location)
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="location_permissions[]"
                                            value="location.{{ $location->id }}" class="input-icheck">
                                        {{ $location->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @if (count($selling_price_groups) > 0)
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <h4>@lang('lang_v1.access_selling_price_groups')</h4>
                        </div>
                        <div class="col-md-9">
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="permissions[]" value="access_default_selling_price"
                                            class="input-icheck" checked>
                                        {{ __('lang_v1.default_selling_price') }}
                                    </label>
                                </div>
                            </div>
                            @foreach ($selling_price_groups as $group)
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="spg_permissions[]"
                                                value="selling_price_group.{{ $group->id }}" class="input-icheck">
                                            {{ $group->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @include('role.partials.module_permissions')
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
                    </div>
                </div>


            </form>
        @endcomponent
    </section>
    <!-- /.content -->
@endsection
