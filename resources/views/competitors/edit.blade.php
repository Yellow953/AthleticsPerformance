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
                <strong>Update Competitor</strong>
            </div>
            <div class="card-body card-block">
                <form action="/competitor/{{$competitor->id}}/update" method="post" enctype="multipart/form-data"
                    class="form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="name" class=" form-control-label">Name*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="name" name="name" value="{{$competitor->name}}"
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
                                        @foreach ($athletes as $athlete)
                                        <option value="{{$athlete->id}}" {{$competitor->athleteID == $athlete->ID ?
                                            'selected' : ''}}>{{$athlete->firstName}} {{$athlete->lastName}}
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
                                    <input type="number" id="year" name="year" value="{{$competitor->year}}"
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
                                        @foreach ($genders as $gender)
                                        <option value="{{$gender->gender}}" {{$competitor->gender == $gender->gender ?
                                            'selected' : ''}}>{{$gender->gender}}</option>
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
                                        @foreach ($teams as $team)
                                        <option value="{{$team->ID}}" {{$competitor->teamID == $team->ID ?
                                            'selected' : ''}}>{{$team->name}}</option>
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
                                        @foreach ($age_groups as $age_group)
                                        <option value="{{$age_group->ID}}" {{$competitor->ageGroupID == $age_group->ID ?
                                            'selected' : ''}}>{{$age_group->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="offset-9 col-3">
                            <button type="submit" class="btn btn-primary">Update Competitor</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection