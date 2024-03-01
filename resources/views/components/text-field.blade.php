<div class="control-group form-group">
    @if(isset($label) && !empty($label))
        <label class="form-label">
            <strong>{{@$label}}</strong>
            @if(isset($is_required) && $is_required)
                <sup class="text-danger red">*</sup>
            @endif
        </label>
    @endif
    <input class="form-control {{@$extClass}}" type="{{$type ?? 'text'}}" id="{{@$id}}" name="{{$name}}"
           placeholder="{{@$placeholder}}"
           value="{{@$value}}"
           style="{{@$style}}"
           {{(isset($disabled) && $disabled) ? 'disabled' : ''}}
           {{(isset($readOnly) && $readOnly) ? 'readOnly' : ''}}
           {{(isset($minlength) && $minlength) ? 'minlength='.$minlength : ''}}
           {{(isset($maxlength) && $maxlength) ? 'maxlength='.$maxlength : ''}}

           @if(isset($type) && $type=="email") data-parsley-type="email" @endif
           @if(isset($onlyNumber) && $onlyNumber) data-parsley-pattern="^[0-9 ]+$" data-parsley-pattern-message="Please enter only numbers" @endif
           @if(isset($onlyText) && $onlyText) data-parsley-pattern="^[a-zA-Z ]+$" data-parsley-pattern-message="Please enter only letters" @endif

           data-parsley-trigger="keyup"
           data-parsley-required="{{(isset($is_required) && $is_required) ? 'true' : 'false'}}"
           data-parsley-required="{{(isset($is_required) && $is_required) ? 'true' : 'false'}}"
    >
</div>
