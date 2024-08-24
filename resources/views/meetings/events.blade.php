@extends('layouts.app')

@section('content')

<div class="container">
    <a href="{{ route('meetings') }}" class="mb-3">
        <h3 class="text-white">
            < Back</h3>
    </a>

    <div class="row">
        <div class="col-md-12">
            <div class="card bg-primary">
                <div class="meeting-info mx-4 my-3">
                    <h2>Meeting</h2>
                    <div class="row mt-3 text-dark">
                        <div class="col-md-4">
                            <div><span class="text-white">ShortName:</span> {{ucwords($meeting->shortName)}}</div>
                            <div><span class="text-white">Name:</span> {{ucwords($meeting->name) ?? 'NULL'}}</div>
                            @if(auth()->user()->role == 'admin')
                            <div><span class="text-white">ID:</span> {{$meeting->id}}</div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div><span class="text-white">Start Date:</span> {{$meeting->startDate}}</div>
                            <div><span class="text-white">End Date:</span> {{$meeting->endDate ?? 'NULL'}}</div>
                        </div>
                        <div class="col-md-4">
                            <div><span class="text-white">Age Group:</span> {{$meeting->ageGroup->name}}</div>
                            <div><span class="text-white">Type:</span> {{$meeting->type->name}}</div>
                            <div><span class="text-white">Venue:</span> {{$meeting->venue ?? 'NULL'}}</div>
                            <div><span class="text-white">Country:</span> {{ Helper::get_country_name($meeting->country)
                                }}</div>
                            <div><span class="text-white">Sub Group:</span> {{$meeting->subgroup ?? 'NULL'}}</div>
                            <div><span class="text-white">I/O:</span> {{$meeting->io }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="events mx-4 my-3">
                    <h3>Events</h3>

                    <div id="error-message" class="alert alert-danger my-4" style="display: none;"></div>

                    <table class="events-table mt-3 w-100 mx-2" id="events-table" border="1">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type *</th>
                                <th>Extra</th>
                                <th>Round *</th>
                                <th>Age Group *</th>
                                <th>Gender *</th>
                                <th>Note</th>
                                <th>Distance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($events as $event)
                            <tr class="clickable-row"
                                onclick="window.location.href = '/events/' + {{$event->id}} + '/results'">
                                <td>{{$event->name}}</td>
                                <td>{{$event->type->name}}</td>
                                <td>{{$event->extra}}</td>
                                <td>{{$event->round}}</td>
                                <td>{{$event->ageGroup->name}}</td>
                                <td>{{$event->gender}}</td>
                                <td>{{$event->note}}</td>
                                <td>{{$event->distance}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">No Events for this specific Meeting yet......</td>
                            </tr>
                            @endforelse
                            <form id="createEventForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="meetingID" value="{{$meeting->id}}">
                                <tr>
                                    <td><input type="text" class="form-control" name="name" placeholder="Name" {{
                                            auth()->user()->role != 'admin' ? 'disabled' : '' }}>
                                    </td>
                                    <td>
                                        <select name="typeID" class="form-control" required>
                                            <option value=""></option>
                                            @foreach(Helper::get_event_types() as $event_type)
                                            <option value="{{$event_type->ID}}">{{$event_type->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="extra" placeholder="Extra"></td>
                                    <td>
                                        <select name="round" class="form-control" required>
                                            <option value=""></option>
                                            @foreach(Helper::get_rounds() as $round)
                                            <option value="{{$round->ID}}">{{$round->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="ageGroupID" class="form-control" required>
                                            <option value=""></option>
                                            @foreach(Helper::get_age_groups() as $age_group)
                                            <option value="{{$age_group->ID}}">{{$age_group->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="gender" class="form-control" required>
                                            <option value=""></option>
                                            @foreach(Helper::get_genders() as $gender)
                                            <option value="{{$gender->gender}}">{{$gender->gender}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="note" placeholder="Note"></td>
                                    <td><input type="number" class="form-control" name="distance"
                                            placeholder="Distance"></td>
                                </tr>
                                <tr style="border:none;">
                                    <td colspan="7"></td>
                                    <td><button type="submit" class="btn btn-block btn-primary"
                                            id="createEventButton">Create</button></td>
                                </tr>
                            </form>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="eventResults"></div>
</div>

{{-- Create live event --}}
<script>
    $(document).ready(function() {
        function updateEventData() {
            var distance = parseInt($('[name="distance"]').val()) || 0;
            var typeID = $('[name="typeID"]').val();

            var deducedName = null;
            var deducedDistance = 0;

            switch (typeID) {
                case 'HM':
                    deducedName = null;
                    deducedDistance = 21000;
                    break;
                case 'M':
                    deducedName = null;
                    deducedDistance = 42000;
                    break;
                case 'UM':
                    deducedName = null;
                    deducedDistance = 100000;
                    break;
                case '03':
                case '04':
                case '05':
                case '07':
                case '08':
                case '10':
                case '1H':
                case '24':
                case 'BT':
                case 'DT':
                case 'HJ':
                case 'HT':
                case 'JT':
                case 'LJ':
                case 'PV':
                case 'SP':
                case 'TJ':
                case 'WT':
                case 'YB':
                    deducedName = null;
                    break;
                case '4R':
                    deducedName = '4x' + distance / 4 + 'm';
                    break;
                default:
                    if (distance === 1600) {
                        deducedName = 'Mile';
                        deducedDistance = 1600;
                    } else if (distance === 6400) {
                        deducedName = '4 Miles';
                        deducedDistance = 6400;
                    } else if (distance === 8000) {
                        deducedName = '5 Miles';
                        deducedDistance = 8000;
                    } else if (distance === 16000) {
                        deducedName = '10 Miles';
                        deducedDistance = 16000;
                    } else if (distance % 1000 === 0) {
                        deducedName = distance / 1000 + 'km';
                        deducedDistance = distance;
                    } else {
                        deducedName = distance + 'm';
                        deducedDistance = distance;
                    }
                    break;
            }

            if (deducedName !== null) {
                $('[name="name"]').val(deducedName);
            }
            if (deducedDistance !== null) {
                $('[name="distance"]').val(deducedDistance);
            }
        }

        $('[name="distance"], [name="typeID"], [name="round"], [name="ageGroupID"], [name="gender"], [name="note"]').change(function () {
            updateEventData();
        });
        
        $('#createEventButton').click(function(event) {
            event.preventDefault();

            var formData = new FormData($('#createEventForm')[0]);
            $.ajax({
                url: "{{ route('meetings.event_create') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var newEvent = response.event;

                    var newRow = $('<tr class="clickable-row" onclick="window.location.href = \'/events/' + newEvent.id + '/results\'">');
                    newRow.append('<td>' + newEvent.name + '</td>');
                    newRow.append('<td>' + newEvent.typeID + '</td>');
                    newRow.append('<td>' + newEvent.extra + '</td>');
                    newRow.append('<td>' + newEvent.round + '</td>');
                    newRow.append('<td>' + newEvent.ageGroupID + '</td>');
                    newRow.append('<td>' + newEvent.gender + '</td>');
                    newRow.append('<td>' + newEvent.note + '</td>');
                    newRow.append('<td>' + newEvent.distance +  '</td>');

                    $('#events-table tbody tr:last-child').prev().before(newRow);

                    $('#createEventForm')[0].reset();

                    $('#error-message').hide();

                    updateEventData();
                },
                error: function(error) {
                    var errorMessage = error.responseJSON.message;
                    $('#error-message').text(errorMessage).show();
                }
            });
        });
    });
</script>

@endsection