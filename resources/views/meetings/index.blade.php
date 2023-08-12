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
                                <div class="row">
                                    <div class="col-3 my-auto">
                                        <img src="{{asset($meeting->image ?? '/assets/images/no_img.png')}}" alt="">
                                    </div>
                                    <div class="col-9 my-auto">
                                        {{ucwords($meeting->shortName)}}<br>
                                        {{ucwords($meeting->name)}}
                                    </div>
                                </div>

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
                                    <a class="item bg-primary d-flex align-items-center justify-content-center"
                                        href="/meeting/{{$meeting->id}}/events" data-toggle="tooltip"
                                        data-placement="top" title="Events">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                                            class="bi bi-cone-striped" viewBox="0 0 16 16">
                                            <path
                                                d="m9.97 4.88.953 3.811C10.159 8.878 9.14 9 8 9c-1.14 0-2.158-.122-2.923-.309L6.03 4.88C6.635 4.957 7.3 5 8 5s1.365-.043 1.97-.120zm-.245-.978L8.97.88C8.718-.13 7.282-.13 7.03.88L6.275 3.9C6.8 3.965 7.382 4 8 4c.618 0 1.2-.036 1.725-.098zm4.396 8.613a.5.5 0 0 1 .037.960l-6 2a.5.5 0 0 1-.316 0l-6-2a.5.5 0 0 1 .037-.960l2.391-.598.565-2.257c.862.212 1.964.339 3.165.339s2.303-.127 3.165-.339l.565 2.257 2.391.598z" />
                                        </svg>
                                    </a>

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