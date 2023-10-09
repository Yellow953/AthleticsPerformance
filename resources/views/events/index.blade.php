@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <div class="table-data__tool">
                <div class="table-data__tool-left">
                    <h3 class="title-5 m-b-35 text-primary">Events</h3>
                </div>
                <div class="table-data__tool-right">
                    <div class="d-flex justify-content-end">
                        <div class="header-button mx-1">
                            <div class="account-wrap">
                                <div class="account-item clearfix js-item-menu">
                                    <div class="content m-0 p-0">
                                        <a class="js-acc-btn text-white btn btn-primary" href="#">Actions</a>
                                    </div>
                                    <div class="account-dropdown js-dropdown bg-light-secondary">
                                        <div class="account-dropdown__body">
                                            <div class="account-dropdown__item">
                                                <a href="/events/new">New Event</a>
                                            </div>
                                            <div class="account-dropdown__item">
                                                <a href="/events/export">Export Events</a>
                                            </div>
                                            @if (auth()->user()->role == 'admin')
                                            <div class="account-dropdown__item">
                                                <a href="/events/upload">Upload Events</a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="header-button mx-1">
                            <div class="account-wrap">
                                <div class="account-item clearfix js-item-menu">
                                    <div class="content m-0 p-0">
                                        <a class="js-acc-btn text-white btn btn-secondary" href="#">Filter</a>
                                    </div>
                                    <div class="account-dropdown js-dropdown bg-light-secondary">
                                        <div class="account-dropdown__body">
                                            <div class="container">
                                                <form action="/events" method="GET" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" name="name" class="form-control" placeholder="Name..." value="{{request()->query('name')}}">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Age Group</label>
                                                                <select name="ageGroupID" class="form-control">
                                                                    <option value="">Age Group</option>
                                                                    @foreach (Helper::get_age_groups() as $age_group)
                                                                    <option value="{{$age_group->ID}}" {{request()->query('ageGroupID') == $age_group->ID ? 'selected' : ''}}>{{$age_group->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Gender</label>
                                                                <select name="gender" class="form-control">
                                                                    <option value="">Gender</option>
                                                                    @foreach (Helper::get_gender() as $gender)
                                                                    <option value="{{$gender->gender}}" {{request()->query('gender') == $gender->gender ? 'selected' : ''}}>{{$gender->gender}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Event Type</label>
                                                                <select name="typeID" class="form-control">
                                                                    <option value="">Event Type</option>
                                                                    @foreach (Helper::get_event_types() as $event_type)
                                                                    <option value="{{$event_type->ID}}" {{request()->query('typeID') == $event_type->ID ? 'selected' : ''}}>{{$event_type->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>I/O</label>
                                                                <select name="io" class="form-control">
                                                                    <option value="">I/O</option>
                                                                    @foreach (Helper::get_ios() as $io)
                                                                    <option value="{{$io->ID}}" {{request()->query('io') == $io->io ? 'selected' : ''}}>{{$io->io}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="actions d-flex justify-content-around">
                                                        <a href="/events" class="btn btn-secondary">Reset</a>
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
                                <span class="block-email">{{$event->meeting->shortName}}</span>
                            </td>
                            <td>
                                Distance: {{$event->distance}} <br>
                                AgeGroup: {{$event->ageGroupID}} <br>
                                Type: {{$event->typeID}}
                            </td>
                            <td>{{$event->created_at}}</td>
                            <td>
                                <div class="table-data-feature">
                                    <a class="item bg-primary d-flex align-items-center justify-content-center"
                                        href="/events/{{$event->id}}/results" data-toggle="tooltip" data-placement="top"
                                        title="Results">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="dark"
                                            class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z" />
                                        </svg>
                                    </a>

                                    @if(auth()->user()->role == 'admin')
                                    @if (!$event->uploaded)
                                    <a class="item bg-success d-flex align-items-center justify-content-center"
                                        href="/events/upload/{{$event->id}}" data-toggle="tooltip" data-placement="top"
                                        title="Upload">
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

                                    <a class="item bg-warning" href="/events/{{$event->id}}/edit" data-toggle="tooltip"
                                        data-placement="top" title="Edit">
                                        <i class="zmdi zmdi-edit text-dark"></i>
                                    </a>
                                    <form method="GET" action="/events/{{$event->id}}/destroy">
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