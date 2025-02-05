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
                <strong>Create Meeting</strong>
            </div>
            <div class="card-body card-block">
                <form action="{{ route('meetings.create') }}" method="post" enctype="multipart/form-data"
                    class="form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="ageGroupID" class=" form-control-label">Age Group *</label>
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
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="io" class=" form-control-label">I/O</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="io" id="io" class="form-control">
                                        @foreach ($ios as $io)
                                        <option value="{{$io->io}}" {{ $io->io == 'O' ? 'selected' : '' }} {{
                                            old('io')==$io->io ?
                                            'selected' : '' }}>{{$io->io}}</option>
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
                                    <label for="shortName" class=" form-control-label">Short Name*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="shortName" name="shortName" placeholder="Short Name" required
                                        value="{{old('shortName')}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="name" class=" form-control-label">Name</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="name" name="name" placeholder="Name" value="{{old('name')}}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="startDate" class=" form-control-label">Start Date*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="date" id="startDate" name="startDate" value="{{old('startDate')}}"
                                        required class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="endDate" class=" form-control-label">End Date</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="date" id="endDate" name="endDate" value="{{old('endDate')}}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="venue" class="form-control-label">Venue*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="venue" name="venue" placeholder="Venue"
                                        value="{{old('venue')}}" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="country" class=" form-control-label">Country*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="country" id="country" class="form-control" required>
                                        @foreach (Helper::Countries() as $key => $country)
                                        <option value="{{$key}}" {{$country=="Lebanon" ? "selected" : "" }}>{{$country}}
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
                                    <label for="typeID" class=" form-control-label">Meeting Type*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="typeID" id="typeID" class="form-control" required>
                                        <option value=""></option>
                                        @foreach ($meeting_types as $meeting_type)
                                        <option value="{{$meeting_type->ID}}" {{ old('typeID')==$meeting_type->ID ?
                                            'selected' : '' }}>{{$meeting_type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="subgroup" class="form-control-label">Sub Group</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="subgroup" name="subgroup" placeholder="Sub Group"
                                        value="{{old('subgroup')}}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="picture" class=" form-control-label">Picture1</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="file" id="picture" name="picture" class="form-control-file"
                                        value="{{old('picture')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="picture2" class=" form-control-label">Picture2</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="file" id="picture2" name="picture2" class="form-control-file"
                                        value="{{old('picture2')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group px-4">
                        <div class="col-6">
                            <div class="checkbox">
                                <label for="isActive" class="form-check-label ">
                                    <input type="checkbox" id="isActive" name="isActive" class="form-check-input"
                                        {{old('isActive') ? 'checked' : '' }}>Active
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="checkbox">
                                <label for="isNew" class="form-check-label ">
                                    <input type="checkbox" id="isNew" name="isNew" class="form-check-input"
                                        {{old('isNew') ? 'checked' : '' }}>Is New
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="offset-9 col-3">
                            <button type="submit" class="btn btn-primary">Create Meeting</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection