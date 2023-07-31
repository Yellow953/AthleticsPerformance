@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <h3 class="title-5 m-b-35 text-primary">Meetings</h3>
            <div class="table-data__tool">
                <div class="table-data__tool-left">
                    <form action="/meetings" method="get" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-8">
                                <input type="text" name="search" id="search" class="form-control"
                                    value="{{request()->query('search')}}" placeholder="Search By Name, Short Name ...">
                            </div>
                            <div class="col-2">
                                <button class="btn btn-primary mx-1" type="submit">
                                    <i class="zmdi zmdi-search mx-1"></i>search</button>
                            </div>
                        </div>
                    </form>


                </div>
                <div class="table-data__tool-right">
                    <a class="btn btn-primary" href="/meeting/new">
                        <i class="zmdi zmdi-plus mx-1"></i>Add Meeting</a>
                    <a href="/meeting/export" class="btn btn-secondary mx-1">Export</a>
                </div>
            </div>
            <div class="table-responsive table-responsive-data2">
                <table class="table table-data2" id="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Details</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($meetings as $meeting)
                        <tr class="tr-shadow">
                            <td>
                                <br>
                                {{ucwords($meeting->name)}} <br>
                                ({{$meeting->shortName}})
                            </td>
                            <td>
                                <span class="block-email m-1">{{$meeting->startDate}}</span>

                                @if ($meeting->endDate)
                                -> <span class="block-email m-1">{{$meeting->endDate}}</span>
                                @endif
                            </td>
                            <td class="text-sm">
                                <small>AgeGroup: {{$meeting->ageGroupID}} <br>
                                    Type: {{$meeting->typeID}} <br>
                                    Venue: {{$meeting->venue}} <br>
                                    Country: {{$meeting->country}} <br>
                                    SubGroup: {{$meeting->subgroup}} <br></small>
                            </td>
                            <td>
                                <div class="table-data-feature">
                                    <a class="item bg-warning" href="/meeting/{{$meeting->id}}/edit"
                                        data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="zmdi zmdi-edit text-dark"></i>
                                    </a>
                                    <form method="GET" action="/meeting/{{$meeting->id}}/destroy">
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
                            <td colspan="4">No Meetings Found ...</td>
                        </tr>
                        @endforelse
                        <tr>
                            <td colspan="4">{{$meetings->appends(['search' => request()->query('search')])->links()}}
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