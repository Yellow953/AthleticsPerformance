@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <div class="table-data__tool">
                <div class="table-data__tool-left">
                    <h3 class="title-5 m-b-35 text-primary">Meetings</h3>
                </div>
                <div class="table-data__tool-right">
                    <div class="d-flex justify-content-end">
                        {{-- <div class="header-button mx-1">
                            <div class="account-wrap">
                                <div class="account-item clearfix js-item-menu">
                                    <div class="content m-0 p-0">
                                        <a class="js-acc-btn text-white btn btn-primary" href="#">Actions</a>
                                    </div>
                                    <div class="account-dropdown js-dropdown bg-light-secondary">
                                        <div class="account-dropdown__body">
                                            <div class="account-dropdown__item">
                                                <a href="/meetings/new">Create Meeting</a>
                                            </div>
                                            <div class="account-dropdown__item">
                                                <a href="/meetings/export">Export Meetings</a>
                                            </div>
                                            @if (auth()->user()->role == 'admin')
                                            <div class="account-dropdown__item">
                                                <a href="/meetings/upload">Upload Meetings</a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <a href="/meetings/new" class="btn btn-primary mx-1 my-auto">Create Meeting</a>
                        <a href="/meetings/export" class="btn btn-primary mx-1 my-auto">Export Meetings</a>
                        @if (auth()->user()->role == 'admin')
                        <a href="/meetings/upload" class="btn btn-primary mx-1 my-auto">Upload Meetings</a>
                        @endif
                        <div class="header-button mx-1 my-auto">
                            <div class="account-wrap">
                                <div class="account-item clearfix js-item-menu">
                                    <div class="content m-0 p-0">
                                        <a class="js-acc-btn text-white btn btn-secondary" href="#">Filter</a>
                                    </div>
                                    <div class="account-dropdown js-dropdown bg-light-secondary">
                                        <div class="account-dropdown__body">
                                            <div class="container">
                                                <form action="/meetings" method="GET" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" name="name" class="form-control"
                                                            placeholder="Name..." value="{{request()->query('name')}}">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Country</label>
                                                                <select name="country" class="form-control">
                                                                    <option value=""></option>
                                                                    @foreach (Helper::Countries() as $index => $country)
                                                                    <option value="{{$index}}" {{request()->
                                                                        query('country') == $index ? 'selected' :
                                                                        ''}}>{{$country}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Venue</label>
                                                                <input type="text" name="venue" class="form-control"
                                                                    placeholder="Venue..."
                                                                    value="{{request()->query('venue')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Age Group</label>
                                                                <select name="ageGroupID" class="form-control">
                                                                    <option value=""></option>
                                                                    @foreach (Helper::get_age_groups() as $age_group)
                                                                    <option value="{{$age_group->ID}}" {{request()->
                                                                        query('ageGroupID') == $age_group->ID ?
                                                                        'selected' : ''}}>{{$age_group->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Meeting Type</label>
                                                                <select name="typeID" class="form-control">
                                                                    <option value=""></option>
                                                                    @foreach (Helper::get_meeting_types() as $meeting_type)
                                                                    <option value="{{$meeting_type->ID}}" {{request()->
                                                                        query('typeID') == $meeting_type->ID ?
                                                                        'selected' : ''}}>{{$meeting_type->name}}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Start Date</label>
                                                                <input type="date" name="startDate" class="form-control"
                                                                    placeholder="Start Date..."
                                                                    value="{{request()->query('startDate')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>End Date</label>
                                                                <input type="date" name="endDate" class="form-control"
                                                                    placeholder="End Date..."
                                                                    value="{{request()->query('endDate')}}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="actions d-flex justify-content-around">
                                                        <a href="/meetings" class="btn btn-secondary">Reset</a>
                                                        <button type="submit" class="btn btn-primary">Apply</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                <small>AgeGroup: {{$meeting->ageGroup->name}} <br>
                                    Type: {{$meeting->type->name}} <br>
                                    Venue: {{$meeting->venue}} <br>
                                    Country: {{ Helper::get_country_name($meeting->country) }} <br>
                                    SubGroup: {{$meeting->subgroup}} <br></small>
                            </td>
                            <td>
                                <div class="table-data-feature">
                                    <a class="item bg-primary d-flex align-items-center justify-content-center"
                                        href="/meetings/{{$meeting->id}}/events" data-toggle="tooltip"
                                        data-placement="top" title="Events">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                                            class="bi bi-cone-striped" viewBox="0 0 16 16">
                                            <path
                                                d="m9.97 4.88.953 3.811C10.159 8.878 9.14 9 8 9c-1.14 0-2.158-.122-2.923-.309L6.03 4.88C6.635 4.957 7.3 5 8 5s1.365-.043 1.97-.120zm-.245-.978L8.97.88C8.718-.13 7.282-.13 7.03.88L6.275 3.9C6.8 3.965 7.382 4 8 4c.618 0 1.2-.036 1.725-.098zm4.396 8.613a.5.5 0 0 1 .037.960l-6 2a.5.5 0 0 1-.316 0l-6-2a.5.5 0 0 1 .037-.960l2.391-.598.565-2.257c.862.212 1.964.339 3.165.339s2.303-.127 3.165-.339l.565 2.257 2.391.598z" />
                                        </svg>
                                    </a>

                                    @if(auth()->user()->role == 'admin')
                                    @if (!$meeting->uploaded)
                                    <a class="item bg-success d-flex align-items-center justify-content-center"
                                        href="/meetings/upload/{{$meeting->id}}" data-toggle="tooltip"
                                        data-placement="top" title="Upload">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                                            class="bi bi-database-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M3.904 1.777C4.978 1.289 6.427 1 8 1s3.022.289 4.096.777C13.125 2.245 14 2.993 14 4s-.875 1.755-1.904 2.223C11.022 6.711 9.573 7 8 7s-3.022-.289-4.096-.777C2.875 5.755 2 5.007 2 4s.875-1.755 1.904-2.223Z" />
                                            <path
                                                d="M2 6.161V7c0 1.007.875 1.755 1.904 2.223C4.978 9.71 6.427 10 8 10s3.022-.289 4.096-.777C13.125 8.755 14 8.007 14 7v-.839c-.457.432-1.004.751-1.49.972C11.278 7.693 9.682 8 8 8s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972Z" />
                                            <path
                                                d="M2 9.161V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13s3.022-.289 4.096-.777C13.125 11.755 14 11.007 14 10v-.839c-.457.432-1.004.751-1.49.972-1.232.56-2.828.867-4.51.867s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972Z" />
                                            <path
                                                d="M2 12.161V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16s3.022-.289 4.096-.777C13.125 14.755 14 14.007 14 13v-.839c-.457.432-1.004.751-1.49.972-1.232.56-2.828.867-4.51.867s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972Z" />
                                        </svg>
                                    </a>
                                    @endif

                                    <a class="item bg-warning" href="/meetings/{{$meeting->id}}/edit"
                                        data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="zmdi zmdi-edit text-dark"></i>
                                    </a>
                                    <form method="GET" action="/meetings/{{$meeting->id}}/destroy">
                                        @csrf
                                        <button class="item bg-danger show_confirm" type="submit" data-toggle="tooltip"
                                            data-placement="top" title="Delete">
                                            <i class="zmdi zmdi-delete text-dark"></i>
                                        </button>
                                    </form>
                                    @endif
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