@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <h3 class="title-5 m-b-35 text-primary">Athletes</h3>
            <div class="table-data__tool">
                <div class="table-data__tool-left">
                    <form action="/athletes" method="get" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-8">
                                <input type="text" name="search" id="search" class="form-control"
                                    value="{{request()->query('search')}}"
                                    placeholder="Search By First Name, Last Name ...">
                            </div>
                            <div class="col-2">
                                <button class="btn btn-primary mx-1" type="submit">
                                    <i class="zmdi zmdi-search mx-1"></i>search</button>
                            </div>
                        </div>
                    </form>


                </div>
                <div class="table-data__tool-right">
                    <a class="btn btn-primary" href="/athlete/new">
                        <i class="zmdi zmdi-plus mx-1"></i>Add Athlete</a>
                    <a href="/athlete/export" class="btn btn-secondary mx-1">Export</a>
                </div>
            </div>
            <div class="table-responsive table-responsive-data2">
                <table class="table table-data2" id="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date of Birth</th>
                            <th>Gender</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($athletes as $athlete)
                        <tr class="tr-shadow">
                            <td>{{ucwords($athlete->firstName)}} {{ucwords($athlete->middleName)}}
                                {{ucwords($athlete->lastName)}}</td>
                            <td>
                                <span class="block-email">{{$athlete->dateOfBirth}}</span>
                            </td>
                            <td>
                                <span
                                    class="block-email text-black {{ $athlete->gender == 'F' ? 'bg-danger' : ''}} {{ $athlete->gender == 'M' ? 'bg-primary' : ''}}">{{$athlete->gender}}</span>
                            </td>
                            <td>
                                <div class="table-data-feature">
                                    <a class="item bg-warning" href="/athlete/{{$athlete->id}}/edit"
                                        data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="zmdi zmdi-edit text-dark"></i>
                                    </a>
                                    <form method="GET" action="/athlete/{{$athlete->id}}/destroy">
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
                            <td colspan="4">No Athletes Found ...</td>
                        </tr>
                        @endforelse
                        <tr>
                            <td colspan="4">{{$athletes->appends(['search' => request()->query('search')])->links()}}
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