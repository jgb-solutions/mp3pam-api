@extends('layouts.admin')

@section('content')
    <div class="block-header">
        <h1>Order ID {{$order->id}} <small>| {{$order->created_at->formatLocalized('%A %e %B %Y')}}</small></h1>
    </div>

    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><h4>Order Detail</h4></div>
                <ul class="list-group">
                    <li class="list-group-item">Price (Gourdes): <b>{{$order->price}}</b></li>
                    <li class="list-group-item">State: <b>{{$order->state}}</b></li>
                    <li class="list-group-item">Branch: <b>{{$order->branch->name}}</b></li>
                    <li class="list-group-item">Client: <b>{{$order->client->name}}</b></li>
                    <li class="list-group-item">Amount: <b>{{$order->amount}}</b></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Client Detail</h4></div>
                <ul class="list-group">
                    <li class="list-group-item">Name: <b>{{$order->client->name}}</b></li>
                    <li class="list-group-item">Username: <b>{{$order->client->username}}</b></li>
                    <li class="list-group-item">Email: <b>{{$order->client->email}}</b></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="block-header">
        <h2>Products</h2>
    </div>

    <div class="card">
        <div class="body">
            <div class="row clearfix">
            @foreach ($order->products as $product)
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
            @endforeach
            </div>
        </div>
    </div>
@stop