@extends('layouts.app')

@section('content')

<div class="container">
    <a href="{{ url()->previous() }}" class="mb-3">
        <h3 class="text-white">
            < Back</h3>
    </a>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Create Athlete</strong>
            </div>
            <div class="card-body card-block">
                <form action="{{ route('athletes.create') }}" method="post" enctype="multipart/form-data"
                    class="form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row form-group">
                                <div class="col col-md-3 my-auto">
                                    <label for="firstName" class=" form-control-label">First Name*</label>
                                </div>
                                <div class="col-12 col-md-9 my-auto">
                                    <input type="text" id="firstName" name="firstName" placeholder="First Name" required
                                        value="{{old('firstName')}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row form-group">
                                <div class="col col-md-3 my-auto">
                                    <label for="middleName" class=" form-control-label">Middle Name</label>
                                </div>
                                <div class="col-12 col-md-9 my-auto">
                                    <input type="text" id="middleName" name="middleName" placeholder="Middle Name"
                                        value="{{old('middleName')}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row form-group">
                                <div class="col col-md-3 my-auto">
                                    <label for="lastName" class=" form-control-label">Last Name*</label>
                                </div>
                                <div class="col-12 col-md-9 my-auto">
                                    <input type="text" id="lastName" name="lastName" placeholder="Last Name" required
                                        value="{{old('lastName')}}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 my-auto">
                            <div class="row form-group">
                                <div class="col col-md-3 my-auto">
                                    <label for="dateOfBirth" class=" form-control-label">Date Of Birth</label>
                                </div>
                                <div class="col-12 col-md-9 my-auto">
                                    <input type="date" id="dateOfBirth" name="dateOfBirth"
                                        value="{{old('dateOfBirth')}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 my-auto">
                            <div class="checkbox mx-4">
                                <label for="exactDate" class="form-check-label ">
                                    <input type="checkbox" id="exactDate" name="exactDate" class="form-check-input" {{
                                        old('exactDate') }}>Exact DOB</label>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-9 my-auto">
                            <div class="row form-group">
                                <div class="col col-md-3 my-auto">
                                    <label for="gender" class=" form-control-label">Gender*</label>
                                </div>
                                <div class="col-12 col-md-9 my-auto">
                                    <select id="gender" name="gender" required class="form-control">
                                        @foreach ($genders as $gender)
                                        <option value="{{$gender->gender}}" {{ old('gender')==$gender->gender ?
                                            'selected' : '' }}>{{$gender->gender}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 my-auto">
                            <div class="checkbox mx-4">
                                <label for="showResult" class="form-check-label ">
                                    <input type="checkbox" id="showResult" name="showResult" class="form-check-input" {{
                                        old('showResult') }}>Show Athlete</label>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="offset-9 col-3">
                            <button type="submit" class="btn btn-primary">Create Athlete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection