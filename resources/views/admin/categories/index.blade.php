@extends('layouts.admin')

@section('content')
    <div class="block-header">
        <h1>{{ $title }}</h1>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-6">
            <div class="card">
                <div class="body table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                @include('admin.categories.category')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    {{$categories->links()}}
                </div>
            </div>
        </div>
    </div>
@stop