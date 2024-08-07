@props([
    'label' => false, 'name', 'value' => ''
    ])  {{--its values aren't included in the attributes array--}}
@if($label)
<label for="">{{$label}}</label>
@endif
<textarea  name="{{$name}}"
    {{$attributes->class([
    'form-control',
    'is-invalid' => $errors->has($name),
])}} {{--accepts any attribute and applys it except the ones in the props--}}
>{{old($name , $value)}}</textarea>
@error($name)
<div class="invalid-feedback">{{ $message }}</div>
@enderror
