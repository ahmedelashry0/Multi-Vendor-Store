@props([
    'id',
    'label' => false,
    'name',
    'value' => ''
])


<label for="{{$id}}">{{$slot}}</label>
