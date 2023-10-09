@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <div class="table-data__tool">
                <div class="table-data__tool-left">
                    <h3 class="title-5 m-b-35 text-primary">Athletes</h3>
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
                                                <a href="/athletes/new">New Athlete</a>
                                            </div>
                                            <div class="account-dropdown__item">
                                                <a href="/athletes/export">Export Athletes</a>
                                            </div>
                                            @if (auth()->user()->role == 'admin')
                                            <div class="account-dropdown__item">
                                                <a href="/athletes/upload">Upload Athletes</a>
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
                                                <form action="/athletes" method="GET" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>First Name</label>
                                                                <input type="text" name="firstName" class="form-control" placeholder="First Name..." value="{{request()->query('firstName')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Last Name</label>
                                                                <input type="text" name="lastName" class="form-control" placeholder="Last Name..." value="{{request()->query('lastName')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Date Of Birth</label>
                                                        <input type="date" name="dob" class="form-control" placeholder="Date Of Birth..." value="{{request()->query('dob')}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Gender</label>
                                                        <select name="gender" class="form-control">
                                                            <option value="">Gender</option>
                                                            @foreach (Helper::get_gender() as $gender)
                                                            <option value="{{$gender->gender}}" {{request()->query('gender') == $gender->gender ? 'selected' : ''}}>{{$gender->gender}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="actions d-flex justify-content-around">
                                                        <a href="/athletes" class="btn btn-secondary">Reset</a>
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
                            <th>Date of Birth</th>
                            <th>Gender</th>
                            @if(auth()->user()->role == 'admin')
                            <th></th>
                            @endif
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
                            @if(auth()->user()->role == 'admin')
                            <td>
                                <div class="table-data-feature">
                                    @if (!$athlete->uploaded)
                                    <a class="item bg-success d-flex align-items-center justify-content-center"
                                        href="/athletes/upload/{{$athlete->id}}" data-toggle="tooltip"
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

                                    <a class="item bg-warning" href="/athletes/{{$athlete->id}}/edit"
                                        data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="zmdi zmdi-edit text-dark"></i>
                                    </a>
                                    <form method="GET" action="/athletes/{{$athlete->id}}/destroy">
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