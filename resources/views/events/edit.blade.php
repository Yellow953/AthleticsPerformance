@extends('layouts.app')

@section('content')

@php
$has_results = $event->results->count() > 0;
@endphp

<div class="container">
    <a href="/events" class="mb-3">
        <h3 class="text-white">
            < Back</h3>
    </a>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Update Event</strong>
            </div>
            <div class="card-body card-block">
                <form action="/events/{{$event->id}}/update" method="post" enctype="multipart/form-data"
                    class="form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="name" class="form-control-label">Name</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{$event->name}}" {{ $has_results ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="meetingID" class="form-control-label">Meeting*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="meetingID" id="meetingID" class="form-control" required {{
                                        $has_results ? 'disabled' : '' }}>
                                        <option value=""></option>
                                        @foreach ($meetings as $meeting)
                                        <option value="{{$meeting->id}}" {{$event->meetingID == $meeting->id
                                            ?
                                            'selected' : ''}}>{{$meeting->shortName}}</option>
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
                                    <label for="distance" class="form-control-label">Distance</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="distance" name="distance" class="form-control"
                                        value="{{$event->distance}}" {{ $has_results ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="typeID" class="form-control-label">Event Type*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="typeID" id="typeID" class="form-control" required {{ $has_results
                                        ? 'disabled' : '' }}>
                                        @foreach ($event_types as $event_type)
                                        <option value="{{$event_type->ID}}" {{$event->typeID == $event_type->ID ?
                                            'selected' : ''}}>{{$event_type->name}}</option>
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
                                    <label for="gender" class="form-control-label">Gender*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="gender" id="gender" class="form-control" required {{ $has_results
                                        ? 'disabled' : '' }}>
                                        @foreach ($genders as $gender)
                                        <option value="{{$gender->gender}}" {{$event->gender ==
                                            $gender->gender ? 'selected' : ''}}>{{$gender->gender}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="ageGroupID" class="form-control-label">Age Group*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="ageGroupID" id="ageGroupID" class="form-control" required {{
                                        $has_results ? 'disabled' : '' }}>
                                        @foreach ($age_groups as $age_group)
                                        <option value="{{$age_group->ID}}" {{$event->ageGroupID == $age_group->ID ?
                                            'selected' : ''}}>{{$age_group->name}}</option>
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
                                    <label for="extra" class="form-control-label">Extra</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="extra" name="extra" class="form-control"
                                        value="{{$event->extra}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="round" class="form-control-label">Round*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="round" id="round" class="form-control" required {{ $has_results
                                        ? 'disabled' : '' }}>
                                        @foreach ($rounds as $round)
                                        <option value="{{$round->ID}}" {{$event->round == $round->ID ? 'selected' :
                                            ''}}>{{$round->name}}</option>
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
                                    <label for="note" class="form-control-label">Note</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="note" name="note" class="form-control"
                                        value="{{$event->note}}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="offset-9 col-3">
                            <button type="submit" class="btn btn-primary">Update Event</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection