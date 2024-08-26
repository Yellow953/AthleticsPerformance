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
                <strong>Create Event</strong>
            </div>
            <div class="card-body card-block">
                <form action="{{ route('events.create') }}" method="post" enctype="multipart/form-data"
                    class="form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="name" class="form-control-label">Name</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="name" name="name" placeholder="Name" class="form-control"
                                        value="{{old('name')}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="meetingID" class="form-control-label">Meeting*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="meetingID" id="meetingID" class="form-control" required>
                                        @foreach ($meetings as $meeting)
                                        <option value="{{$meeting->id}}" {{ old('meetingID')==$meeting->id ? 'selected'
                                            : '' }}>{{$meeting->shortName}}</option>
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
                                    <label for="typeID" class="form-control-label">Event Type*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="typeID" id="typeID" class="form-control" required>
                                        @foreach ($event_types as $event_type)
                                        <option value="{{$event_type->ID}}" {{ old('typeID')==$event_type->ID ?
                                            'selected' : '' }}>{{$event_type->name}}</option>
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
                                    <select name="ageGroupID" id="ageGroupID" class="form-control" required>
                                        <option value=""></option>
                                        @foreach ($age_groups as $age_group)
                                        <option value="{{$age_group->ID}}" {{ old('ageGroupID')==$age_group->ID ?
                                            'selected' : '' }}>{{$age_group->name}}</option>
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
                                    <input type="text" id="extra" name="extra" placeholder="Extra" class="form-control"
                                        value="{{ old('extra') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="round" class="form-control-label">Round*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="round" id="round" class="form-control" required>
                                        @foreach ($rounds as $round)
                                        <option value="{{$round->ID}}" {{$round->name == "Final" ? 'selected' :
                                            ''}}>{{$round->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="gender" class="form-control-label">Gender*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="gender" id="gender" class="form-control" required>
                                        @foreach ($genders as $gender)
                                        <option value="{{$gender->gender}}" {{ old('gender')==$gender->gender ?
                                            'selected' : '' }}>{{$gender->gender}}</option>
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
                                    <label for="wind" class="form-control-label">Wind</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="wind" name="wind" placeholder="Wind" class="form-control"
                                        value="{{old('wind')}}" step="0.1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="note" class="form-control-label">Note</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="note" name="note" placeholder="Note" class="form-control"
                                        value="{{old('note')}}">
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
                                    <input type="number" id="distance" name="distance" placeholder="Distance"
                                        class="form-control" value="{{old('distance') ?? 0}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="heat" class="form-control-label">Heat</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="heat" name="heat" placeholder="Heat" class="form-control"
                                        value="{{old('heat')}}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="offset-9 col-3">
                            <button type="submit" class="btn btn-primary">Create Event</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection