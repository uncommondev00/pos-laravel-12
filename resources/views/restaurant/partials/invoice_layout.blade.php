@php
$default = [];
$default['show_table'] = 1;
$default['table_label'] = 'Table';

$default['show_service_staff'] = 1;
$default['service_staff_label'] = 'Service staff';

if(!empty($edit_il)){
$default['show_table'] = isset($module_info['tables']['show_table']) ? $module_info['tables']['show_table'] : 0;
$default['table_label'] = isset($module_info['tables']['table_label']) ? $module_info['tables']['table_label'] : '';

$default['show_service_staff'] = isset($module_info['service_staff']['show_service_staff']) ? $module_info['service_staff']['show_service_staff'] : 0;

$default['service_staff_label'] = isset($module_info['service_staff']['service_staff_label']) ? $module_info['service_staff']['service_staff_label'] : '';
}
@endphp
@if(!empty($enabled_modules))
<div class="box box-solid">
	<div class="box-body">
		<div class="box-header">
			<h3 class="box-title">@lang('restaurant.modules_settings')</h3>
		</div>
		<div class="row">
			@if(in_array('tables', $enabled_modules) )
			<div class="col-sm-3">
				<div class="form-group">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="module_info[tables][show_table]" value="1" class="input-icheck" {{ $default['show_table'] ? 'checked' : '' }}> @lang('restaurant.show_table')
						</label>
					</div>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<label for="module_info[tables][table_label]">@lang('restaurant.table_label'):</label>
					<input type="text" name="module_info[tables][table_label]" id="module_info[tables][table_label]" class="form-control" placeholder="@lang('restaurant.table_label')" value="{{ $default['table_label'] }}">
				</div>
			</div>
			@endif
			@if(in_array('service_staff', $enabled_modules) )
			<div class="col-sm-3">
				<div class="form-group">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="module_info[service_staff][show_service_staff]" value="1" class="input-icheck" {{ $default['show_service_staff'] ? 'checked' : '' }}> @lang('restaurant.show_service_staff')
						</label>
					</div>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<label for="module_info[service_staff][service_staff_label]">@lang('restaurant.service_staff_label'):</label>
					<input type="text" name="module_info[service_staff][service_staff_label]" id="module_info[service_staff][service_staff_label]" class="form-control" placeholder="@lang('restaurant.service_staff_label')" value="{{ $default['service_staff_label'] }}">
				</div>
			</div>
			@endif

		</div>
	</div>
</div>
@endif
