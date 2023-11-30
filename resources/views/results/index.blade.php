@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <div class="table-data__tool">
                <div class="table-data__tool-left">
                    <h3 class="title-5 m-b-35 text-primary my-auto">Results</h3>
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
                                            <!-- <div class="account-dropdown__item">
                                                <a href="/results/new">Create Result</a>
                                            </div> -->
                                            <div class="account-dropdown__item">
                                                <a href="/results/export">Export Results</a>
                                            </div>
                                            @if (auth()->user()->role == 'admin')
                                            <div class="account-dropdown__item">
                                                <a href="/results/upload">Upload Results</a>
                                            </div>
                                            <div class="account-dropdown__item">
                                                <a href="/results/scoring">Scoring</a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <a href="/results/export" class="btn btn-primary mx-1 my-auto">Export Results</a>
                        @if (auth()->user()->role == 'admin')
                        <a href="/results/upload" class="btn btn-primary mx-1 my-auto">Upload Results</a>
                        <a href="/results/scoring" class="btn btn-primary mx-1 my-auto">Scoring</a>
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
                                                <form action="/results" method="GET" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label>Event</label>
                                                        <select name="eventID" class="form-control">
                                                            @foreach ($events as $event)
                                                            <option value="{{$event->id}}" {{request()->query('eventID')
                                                                == $event->id ? 'selected' : ''}}>{{$event->name}}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Competitor</label>
                                                        <select name="competitorID" class="form-control">
                                                            @foreach ($competitors as $competitor)
                                                            <option value="{{$competitor->id}}" {{request()->
                                                                query('competitorID') == $competitor->id ? 'selected' :
                                                                ''}}>{{$competitor->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="actions d-flex justify-content-around">
                                                        <a href="/results" class="btn btn-secondary">Reset</a>
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
                            <th>Meeting</th>
                            <th>Event</th>
                            <th>Competitor</th>
                            <th>Result</th>
                            <th>Date</th>
                            @if(auth()->user()->role == 'admin')
                            <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($results as $result)
                        <tr class="tr-shadow">
                            <td>{{$result->event->meeting->shortName}}</td>
                            <td>{{$result->event->name}}</td>
                            <td>{{$result->competitor->name ?? ''}}</td>
                            <td>
                                <div class="row">
                                    <div class="col-md-6">Position: {{$result->position}}</div>
                                    <div class="col-md-6">Result: {{$result->result}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">Points: {{$result->points}}</div>
                                    <div class="col-md-6">Result Value: {{$result->resultValue}}</div>
                                </div>
                            </td>
                            <td>{{$result->created_at}}</td>
                            @if(auth()->user()->role == 'admin')
                            <td>
                                <div class="table-data-feature">
                                    <a class="item bg-primary d-flex align-items-center justify-content-center"
                                        href="/results/{{$result->id}}/new_record" data-toggle="tooltip"
                                        data-placement="top" title="Create Record">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                                            class="bi bi-stars" viewBox="0 0 16 16">
                                            <path
                                                d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828l.645-1.937zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.734 1.734 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.734 1.734 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.734 1.734 0 0 0 3.407 2.31l.387-1.162zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L10.863.1z" />
                                        </svg>
                                    </a>

                                    @if (!$result->uploaded)
                                    <a class="item bg-success d-flex align-items-center justify-content-center"
                                        href="/results/upload/{{$result->id}}" data-toggle="tooltip"
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

                                    <a class="item bg-warning" href="/results/{{$result->id}}/edit"
                                        data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="zmdi zmdi-edit text-dark"></i>
                                    </a>
                                    <form method="GET" action="/results/{{$result->id}}/destroy">
                                        @csrf
                                        <button class="item bg-danger show_confirm" type="submit" data-toggle="tooltip"
                                            data-placement="top" title="Delete">
                                            <i class="zmdi zmdi-delete text-dark"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        <tr class="spacer"></tr>
                        @empty
                        <tr>
                            <td colspan="6">No Results Found ...</td>
                        </tr>
                        @endforelse
                        <tr>
                            <td colspan="6">{{$results->appends(['search' => request()->query('search')])->links()}}
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