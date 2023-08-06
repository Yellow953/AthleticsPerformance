@extends('layouts.app')

@section('content')

<div class="container">
    <a href="/records" class="mb-3">
        <h3 class="text-white">
            < Back</h3>
    </a>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Create Record</strong>
            </div>
            <div class="card-body card-block">
                <form action="/record/create" method="post" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="name" class=" form-control-label">Name</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="name" name="name" placeholder="Name" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="venue" class=" form-control-label">Venue</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="venue" name="venue" placeholder="Venue" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="date" class=" form-control-label">Date1</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="date" id="date" name="date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="date2" class=" form-control-label">Date2</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="date" id="date2" name="date2" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="competitor" class=" form-control-label">Competitor</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="competitor" name="competitor" placeholder="Competitor"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="athleteID" class=" form-control-label">Athlete</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="athleteID" id="athleteID" class="form-control">
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
                                    <label for="ageGroupID" class=" form-control-label">Age Group</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="ageGroupID" id="ageGroupID" class="form-control">
                                        @foreach ($age_groups as $age_group)
                                        <option value="{{$age_group->ID}}">{{$age_group->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="typeID" class=" form-control-label">Event Type</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="typeID" id="typeID" class="form-control">
                                        @foreach ($event_types as $event_type)
                                        <option value="{{$event_type->ID}}">{{$event_type->name}}</option>
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
                                    <label for="teamID" class=" form-control-label">Team</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="teamID" id="teamID" class="form-control">
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
                                    <label for="gender" class=" form-control-label">Gender</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="gender" id="gender" class="form-control">
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
                                    <label for="wind" class=" form-control-label">Wind</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="wind" name="wind" placeholder="Wind" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="note" class=" form-control-label">Note</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="note" name="note" placeholder="Note" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="distance" class=" form-control-label">Distance</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="distance" name="distance" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="result" class=" form-control-label">Result</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="result" name="result" step="0.01" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="points" class=" form-control-label">Points</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="points" name="points" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="resultValue" class=" form-control-label">Result Value</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="resultValue" name="resultValue" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="extra" class=" form-control-label">Extra</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="extra" name="extra" placeholder="Extra" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-4">
                        <div class="col-md-3">
                            <div class="checkbox">
                                <label for="current" class="form-check-label ">
                                    <input type="checkbox" id="current" name="current" class="form-check-input">Current
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="offset-9 col-3">
                            <button type="submit" class="btn btn-primary">Create Record</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection