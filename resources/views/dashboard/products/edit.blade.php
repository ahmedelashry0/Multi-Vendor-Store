@extends('layouts.dashboard')

@section('title', 'Edit product')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Categories</li>
    <li class="breadcrumb-item active">Edit Product</li>
@endsection
@section('content')

    <form action="{{route('dashboard.products.update' , $product->id)}}" method="post">
        @csrf
        @method('put')
        @include('dashboard.products._form' , [
            'button_label' => 'Update',
])
    </form>

@endsection
