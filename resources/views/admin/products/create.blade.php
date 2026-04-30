@extends('admin.sidebar')

@section('content')
@include('admin.products._styles')

<div class="product-admin-page">
    <div class="product-page-header">
        <div>
            <h1>Add Product</h1>
            <p>Create a product for customer checkout and POS inventory.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.products._form')
    </form>
</div>
@endsection
