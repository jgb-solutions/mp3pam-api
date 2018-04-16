@extends('layouts.admin')

@section('content')
    <div class="block-header">
        <h1>DASHBOARD</h1>
    </div>

    <div class="block-header">
        <h2>Overview</h2>
    </div>

    <!-- Widgets -->
    <div class="row clearfix">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <div class="info-box bg-pink hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">shopping_cart</i>
                </div>
                <div class="content">
                    <div class="text">Orders</div>
                    <div class="number count-to" data-from="0" data-to="{{$orders_count}}" data-speed="15" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <div class="info-box bg-cyan hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">help</i>
                </div>
                <div class="content">
                    <div class="text">Menu</div>
                    <div class="number count-to" data-from="0" data-to="{{$categories_count}}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <div class="info-box bg-light-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">forum</i>
                </div>
                <div class="content">
                    <div class="text">Products</div>
                    <div class="number count-to" data-from="0" data-to="{{$products_count}}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <div class="info-box bg-orange hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">person_add</i>
                </div>
                <div class="content">
                    <div class="text">Gifts</div>
                    <div class="number count-to" data-from="0" data-to="{{$gifts_count}}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <div class="info-box bg-brown hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">build</i>
                </div>
                <div class="content">
                    <div class="text">services</div>
                    <div class="number count-to" data-from="0" data-to="{{$services_count}}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <div class="info-box bg-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">business_center</i>
                </div>
                <div class="content">
                    <div class="text">Branches</div>
                    <div class="number count-to" data-from="0" data-to="{{$branches_count}}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Widgets -->
    {{-- <br>
    <div class="block-header">
        <h2>Latest Orders</h2>
    </div> --}}
    <br>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-6">
            <div class="card">
                <div class="header">
                    <div class="row">
                        <div class="col-xs-6">
                            <h2>
                                Latest Orders
                            </h2>
                        </div>
                        <div class="col-xs-6 text-right">
                           {{--  <a href="{{route('admin.orders.add')}}" type="button" class="btn bg-brown btn-circle waves-effect waves-circle waves-float">
                                <i class="material-icons">add</i>
                            </a> --}}
                            <a href="{{route('admin.orders.index')}}">
                                All orders
                            </a>
                        </div>
                    </div>
                </div>
                <div class="body table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Price (Gourdes)</th>
                                <th>State</th>
                                <th>Branch</th>
                                <th>Owner</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                @include('admin.orders.order')
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop