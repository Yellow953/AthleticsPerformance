@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <h3 class="title-5 m-b-35 text-primary">Results</h3>
            <div class="table-data__tool">
                <div class="table-data__tool-left">
                    <form action="/results" method="get" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-8">
                                <input type="text" name="search" id="search" class="form-control"
                                    value="{{request()->query('search')}}" placeholder="Search By Name...">
                            </div>
                            <div class="col-2">
                                <button class="btn btn-primary mx-1" type="submit">
                                    <i class="zmdi zmdi-search mx-1"></i>search</button>
                            </div>
                        </div>
                    </form>


                </div>
                <div class="table-data__tool-right">
                    <a class="btn btn-primary" href="/result/new">
                        <i class="zmdi zmdi-plus mx-1"></i>Add Record</a>
                    <a href="/result/export" class="btn btn-secondary mx-1">Export</a>
                </div>
            </div>
            <div class="table-responsive table-responsive-data2">
                <table class="table table-data2" id="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($results as $result)
                        <tr class="tr-shadow">
                            <td>{{$result->created_at}}</td>
                            <td>
                                <div class="table-data-feature">
                                    <a class="item bg-warning" href="/result/{{$result->id}}/edit" data-toggle="tooltip"
                                        data-placement="top" title="Edit">
                                        <i class="zmdi zmdi-edit text-dark"></i>
                                    </a>
                                    <form method="GET" action="/result/{{$result->id}}/destroy">
                                        @csrf
                                        <button class="item bg-danger show_confirm" type="submit" data-toggle="tooltip"
                                            data-placement="top" title="Delete">
                                            <i class="zmdi zmdi-delete text-dark"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr class="spacer"></tr>
                        @empty
                        <tr>
                            <td colspan="5">No Results Found ...</td>
                        </tr>
                        @endforelse
                        <tr>
                            <td colspan="5">{{$results->appends(['search' => request()->query('search')])->links()}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- END DATA TABLE -->
        </div>
    </div>
</div>

@endsection