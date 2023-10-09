@extends('layouts.app')

@section('content')

<div class="container">
    <a href="/records" class="mb-3">
        <h3 class="text-white">
            < Back</h3>
    </a>

    <div class="row">
        <div class="col-md-12">
            <div class="card bg-primary">
                <div class="m-4">
                    <h2 class="my-3">Record</h2>
                    <div class="row text-dark">
                        <div class="col-md-4">
                            ResultID: {{$result->id}} <br>
                            Result: {{$result->result}} <br>
                            Points: {{$result->points}} <br>
                            ResultValue: {{$result->resultValue}} <br>
                        </div>
                        <div class="col-md-4">
                            Event Name: {{$event->name}} <br>
                            Event Type: {{$event->typeID}} <br>
                            Extra: {{$event->extra}} <br>
                            Gender: {{$event->gender}} <br>
                            Distance: {{$event->distance}} <br>
                            IO: {{$event->io}} <br>
                        </div>
                        <div class="col-md-4">
                            Competitor: {{$competitor->name}} <br>
                            TeamID: {{$competitor->teamID}} <br>
                            AthleteID: {{$competitor->athlete->firstName ?? ''}} {{$competitor->athlete->middleName ??
                            ''}} {{$competitor->athlete->lastName ??
                            ''}} <br>
                            Venue: {{$meeting->venue}} <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="/results/{{$result->id}}/create_record" method="post" enctype="multipart/form-data"
                    class="form-horizontal m-4">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="date" class=" form-control-label">Actual Date *</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="date" id="date" name="date" class="form-control" value="{{$meeting->startDate ?? ''}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="date2" class=" form-control-label">Display Date *</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="date2" name="date2" class="form-control" value="{{$meeting->startDate ?? ''}}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="ageGroupID" class=" form-control-label">Age Group *</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="ageGroupID" id="ageGroupID" class="form-control" required>
                                        <option value="">Pick an Age Group</option>
                                        @foreach ($age_groups as $age_group)
                                        <option value="{{$age_group->ID}}">{{$age_group->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
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
                        <div class="offset-md-6 col-md-3">
                            <div class="checkbox">
                                <label for="wind" class="form-check-label ">
                                    <input type="checkbox" id="wind" name="wind" class="form-check-input">Wind (i)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="checkbox">
                                <label for="current" class="form-check-label ">
                                    <input type="checkbox" id="current" name="current" class="form-check-input">Current
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end my-3">
                        <button type="submit" class="btn btn-primary">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection