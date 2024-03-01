<div class="control-group form-group @if(isset($is_hide) && $is_hide) d-none @endif">
    <label class="form-label">
        <strong>{!! $label !!}</strong>
        @if(isset($is_required) && $is_required)
            <sup class="text-danger red">*</sup>
        @endif
    </label>
    @php $error_container_id = str_replace('[]', '_', $id).mt_rand(100, 9999); @endphp
    <select class="default-select form-control wide {{@$extClass}}"
            data-minimum-results-for-search="Infinity"
            name="{{$name}}"
            id="{{$id}}"
            data-parsley-required-message="{{@$placeholder}}"
            data-parsley-errors-container="#{{ $error_container_id }}_errorContainer"
            @if(isset($is_required) && $is_required) data-parsley-required="true" @endif
            @if(isset($is_multiple) && $is_multiple) multiple @endif>
        @if(isset($placeholder))
            <option value="" disabled>{{$placeholder}}</option>
        @endif
        @foreach($options as $key => $value)
            <option
                @if(isset($selected_value) && !isset($is_multiple))
                {{$selected_value == $key ? 'selected' : ''}}
                @endif

                @if(isset($selected_value) && !empty($selected_value) && isset($is_multiple) && $is_multiple)
                {{ in_array($key,$selected_value) ? 'selected' : ''}}
                @endif

                value="{{$key}}">{{ucfirst($value)}}</option>
        @endforeach
    </select>
    <span class="error text-danger form-errors d-none "></span>
    <div class="ProductError"></div>
    <div id="{{ $error_container_id }}_errorContainer"></div>
</div>
