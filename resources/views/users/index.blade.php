@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <h3 class="title-5 m-b-35 text-primary">Users</h3>
            <div class="table-data__tool">
                <div class="table-data__tool-left">
                    <form action="/users" method="get" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-8">
                                <input type="text" name="search" id="search" class="form-control"
                                    value="{{request()->query('search')}}" placeholder="Search By Name, Email ...">
                            </div>
                            <div class="col-2">
                                <button class="btn btn-primary mx-1" type="submit">
                                    <i class="zmdi zmdi-search mx-1"></i>search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-data__tool-right">
                    <div class="header-button mt-0">
                        <div class="noti-wrap">
                        </div>
                        <div class="account-wrap">
                            <div class="account-item clearfix js-item-menu">
                                <div class="content">
                                    <a class="js-acc-btn text-white" href="#">Actions</a>
                                </div>
                                <div class="account-dropdown js-dropdown bg-light-secondary">
                                    <div class="account-dropdown__body">
                                        <div class="account-dropdown__item">
                                            <a href="/user/new">New User</a>
                                        </div>
                                        <div class="account-dropdown__item">
                                            <a href="/users/export">Export Users</a>
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
                            <th>Email</th>
                            <th>Role</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr class="tr-shadow">
                            <td>{{ucwords($user->name)}}</td>
                            <td>
                                <span class="block-email">{{$user->email}}</span>
                            </td>
                            <td> <span
                                    class="{{ $user->role == 'admin' ? 'status--process' : ''}}">{{ucwords($user->role)}}</span>
                            </td>
                            <td>{{$user->created_at}}</td>
                            <td>
                                <div class="table-data-feature">
                                    <a class="item bg-warning" href="/user/{{$user->id}}/edit" data-toggle="tooltip"
                                        data-placement="top" title="Edit">
                                        <i class="zmdi zmdi-edit text-dark"></i>
                                    </a>
                                    <form method="GET" action="/user/{{$user->id}}/destroy">
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
                            <td colspan="5">No Users Found ...</td>
                        </tr>
                        @endforelse
                        <tr>
                            <td colspan="5">{{$users->appends(['search' => request()->query('search')])->links()}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- END DATA TABLE -->
        </div>
    </div>
</div>
@endsection