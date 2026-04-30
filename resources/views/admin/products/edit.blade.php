@extends('admin.sidebar')

@section('content')
@include('admin.products._styles')

<div class="product-admin-page">
    <div class="product-page-header">
        <div>
            <h1>Edit Product</h1>
            <p>Update pricing, stock, display image, and product availability.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.products._form')
    </form>
</div>
@endsection
