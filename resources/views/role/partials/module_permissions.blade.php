@if(count($module_permissions) > 0)
    @foreach($module_permissions as $key => $value)
        <hr>
        <div class="row check_group">
            <div class="col-md-3">
                <h4>{{ $key }}</h4>
            </div>
            <div class="col-md-9">
                @foreach($value as $module_permission)
                    @php
                        $isChecked = !empty($role_permissions) ? in_array($module_permission['value'], $role_permissions) 
                                   : ($module_permission['default'] ? true : false);
                    @endphp
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="permissions[]" value="{{ $module_permission['value'] }}" class="input-icheck" 
                                       {{ $isChecked ? 'checked' : '' }}>
                                {{ $module_permission['label'] }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@endif
