@extends('layouts.admin')

@section('content')
    <div class="block-header">
        <h1>Category ID {{$category->id}} <small>| {{$category->created_at->formatLocalized('%A %e %B %Y')}}</small></h1>
    </div>

    <div class="block-header">
        <h2>Products</h2>
    </div>

    <div class="card">
        <div class="body">
            <div class="row clearfix">
            @foreach ($category->products as $product)
                <div class="col-sm-12">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <img src="http://via.placeholder.com/500x500" class="img-responsive">
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                        <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading"><h4>Product Detail</h4></div>
                        <ul class="list-group">
                            <li class="list-group-item">ID: <b>{{$product->id}}</b></li>
                            <li class="list-group-item">Name: <b>{{$product->name}}</b></li>
                            <li class="list-group-item">Price (Gourdes): <b>{{$product->price}}</b></li>
                            <li class="list-group-item">Category: <b>{{$product->category->name}}</b></li>
                            @if ($product->description)
                                <li class="list-group-item">Description: <b>{{$product->description}}</b></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
        </div>
    </div>
@stop