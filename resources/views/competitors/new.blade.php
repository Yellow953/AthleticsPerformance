@extends('layouts.app')

@section('content')

<div class="container">
    <a href="/competitors" class="mb-3">
        <h3 class="text-white">
            < Back</h3>
    </a>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Create Competitor</strong>
            </div>
            <div class="card-body card-block">
                <form action="/competitor/create" method="post" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="name" class=" form-control-label">Name*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="name" name="name" value="{{old('name')}}"
                                        class="form-control" selected>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="athleteID" class=" form-control-label">Athlete*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select id="athleteID" name="athleteID" required class="form-control">
                                        <option>Select Athlete</option>
                                        @foreach ($athletes as $athlete)
                                        <option value="{{$athlete->id}}">{{$athlete->firstName}} {{$athlete->lastName}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="year" class=" form-control-label">Year*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="year" name="year" value="{{old('year')}}"
                                        class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="gender" class=" form-control-label">Gender*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select id="gender" name="gender" class="form-control" required>
                                        <option>Select Gender</option>
                                        @foreach ($genders as $gender)
                                        <option value="{{$gender->gender}}">{{$gender->gender}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="teamID" class=" form-control-label">Team*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select id="teamID" name="teamID" class="form-control" required>
                                        <option>Select Team</option>
                                        @foreach ($teams as $team)
                                        <option value="{{$team->ID}}">{{$team->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="ageGroupID" class=" form-control-label">Age Group*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select id="ageGroupID" name="ageGroupID" class="form-control" required>
                                        <option>Select Age Group</option>
                                        @foreach ($age_groups as $age_group)
                                        <option value="{{$age_group->ID}}">{{$age_group->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="offset-9 col-3">
                            <button type="submit" class="btn btn-primary">Create Competitor</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection