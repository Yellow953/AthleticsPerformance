@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <h3 class="title-5 m-b-35 text-primary">Events</h3>
            <div class="table-data__tool">
                <div class="table-data__tool-left">
                    <form action="/evetsn" method="get" enctype="multipart/form-data">
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
                    <a class="btn btn-primary" href="/event/new">
                        <i class="zmdi zmdi-plus mx-1"></i>Add Event</a>
                    <a href="/event/export" class="btn btn-secondary mx-1">Export</a>
                </div>
            </div>
            <div class="table-responsive table-responsive-data2">
                <table class="table table-data2" id="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Meeting</th>
                            <th>Details</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                        <tr class="tr-shadow">
                            <td>{{ucwords($event->name)}}</td>
                            <td>
                                <span class="block-email">{{$event->meetingID}}</span>
                            </td>
                            <td>
                                Distance: {{$event->distance}} <br>
                                AgeGroup: {{$event->ageGroupID}} <br>
                                Type: {{$event->typeID}}
                            </td>
                            <td>{{$event->created_at}}</td>
                            <td>
                                <div class="table-data-feature">
                                    <a class="item bg-warning" href="/event/{{$event->id}}/edit" data-toggle="tooltip"
                                        data-placement="top" title="Edit">
                                        <i class="zmdi zmdi-edit text-dark"></i>
                                    </a>
                                    <form method="GET" action="/event/{{$event->id}}/destroy">
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
                            <td colspan="5">No Event Found ...</td>
                        </tr>
                        @endforelse
                        <tr>
                            <td colspan="5">{{$events->appends(['search' => request()->query('search')])->links()}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- END DATA TABLE -->
        </div>
    </div>
</div>

@endsection