@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <div class="table-data__tool">
                <div class="table-data__tool-left">
                    <h3 class="title-5 m-b-35 text-primary">Users</h3>
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
                                                <a href="/users/new">Create User</a>
                                            </div>
                                            <div class="account-dropdown__item">
                                                <a href="/users/export">Export Users</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <a href="/users/new" class="btn btn-primary mx-1 my-auto">Create User</a>
                        <a href="/users/export" class="btn btn-primary mx-1 my-auto">Export Users</a>
                        <div class="header-button mx-1 my-auto">
                            <div class="account-wrap">
                                <div class="account-item clearfix js-item-menu">
                                    <div class="content m-0 p-0">
                                        <a class="js-acc-btn text-white btn btn-secondary" href="#">Filter</a>
                                    </div>
                                    <div class="account-dropdown js-dropdown bg-light-secondary">
                                        <div class="account-dropdown__body">
                                            <div class="container">
                                                <form action="/users" method="GET" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" name="name" class="form-control"
                                                            placeholder="Name..." value="{{request()->query('name')}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control"
                                                            placeholder="Email..."
                                                            value="{{request()->query('email')}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Role</label>
                                                        <select name="role" class="form-control">
                                                            <option value=""></option>
                                                            <option value="user" {{request()->query('role') == 'user' ?
                                                                'selected' : ''}}>User</option>
                                                            <option value="admin" {{request()->query('role') == 'admin'
                                                                ? 'selected' : ''}}>Admin</option>
                                                        </select>
                                                    </div>

                                                    <div class="actions d-flex justify-content-around">
                                                        <a href="/users" class="btn btn-secondary">Reset</a>
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
                                    <a class="item bg-warning" href="/users/{{$user->id}}/edit" data-toggle="tooltip"
                                        data-placement="top" title="Edit">
                                        <i class="zmdi zmdi-edit text-dark"></i>
                                    </a>
                                    <form method="GET" action="/users/{{$user->id}}/destroy">
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
                            <td colspan="5">{{$users->appends(['name' => request()->query('name'), 'email' =>
                                request()->query('email'), 'role' => request()->query('role')])->links()}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- END DATA TABLE -->
        </div>
    </div>
</div>
@endsection