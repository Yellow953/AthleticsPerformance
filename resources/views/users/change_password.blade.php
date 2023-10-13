@extends('layouts.app')

@section('content')

<div class="container">
    <a href="/" class="mb-3">
        <h3 class="text-white">
            < Back</h3>
    </a>

    @php
        $user = auth()->user();
    @endphp

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Update User</strong>
            </div>
            <div class="card-body card-block">
                <form action="/users/{{$user->id}}/change_password" method="post" enctype="multipart/form-data"
                    class="form-horizontal">
                    @csrf
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="new_password" class=" form-control-label">New Password *</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="password" id="new_password" name="new_password" placeholder="New Password" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="confirm_password" class=" form-control-label">Password Confirmation *</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="form-control">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="offset-9 col-3">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection