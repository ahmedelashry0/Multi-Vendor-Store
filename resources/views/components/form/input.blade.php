@props([
    'label' => false,'type' => 'text', 'name', 'value' => ''
    ])  {{--its values aren't included in the attributes array--}}
@if($label)
<label for="">{{$label}}</label>
@endif
<input type="{{$type}}" name="{{$name}}"
       value="{{old($name , $value)}}"
    {{$attributes->class([
    'form-control',
    'is-invalid' => $errors->has($name),
])}} {{--accepts any attribute and applys it except the ones in the props--}}
>
@error($name)
<div class="invalid-feedback">{{ $message }}</div>
@enderror
