@extends('layouts.admin')

@section('content')
    <div class="block-header">
        <h1>All Orders</h1>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-6">
            <div class="card">
                <div class="body table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Price (Gourdes)</th>
                                <th>State</th>
                                <th>Branch</th>
                                <th>Client</th>
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
                <div class="text-center">
                    {{$orders->links()}}
                </div>
            </div>
        </div>
    </div>
@stop