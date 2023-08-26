@extends('layouts.app')

@section('content')

<div class="container">
    <a href="/meetings" class="mb-3">
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
                            <div><span class="text-white">Name:</span> {{ucwords($meeting->name) ?? 'NULL'}}</div>
                            <div><span class="text-white">ShortName:</span> {{ucwords($meeting->shortName)}}</div>
                            <div><span class="text-white">ID:</span> {{$meeting->IDSecond}}</div>
                        </div>
                        <div class="col-md-4">
                            <div><span class="text-white">Start Date:</span> {{$meeting->startDate}}</div>
                            <div><span class="text-white">End Date:</span> {{$meeting->endDate ?? 'NULL'}}</div>
                        </div>
                        <div class="col-md-4">
                            <div><span class="text-white">Age Group:</span> {{$meeting->ageGroupID}}</div>
                            <div><span class="text-white">Type:</span> {{$meeting->typeID}}</div>
                            <div><span class="text-white">Venue:</span> {{$meeting->venue ?? 'NULL'}}</div>
                            <div><span class="text-white">Country:</span> {{$meeting->country}}</div>
                            <div><span class="text-white">Sub Group:</span> {{$meeting->subgroup ?? 'NULL'}}</div>
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

                    <table class="events-table mt-3 w-100 mx-2" id="events-table" border="1">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type ID</th>
                                <th>Extra</th>
                                <th>Round</th>
                                <th>Age Group ID</th>
                                <th>Gender</th>
                                <th>Wind</th>
                                <th>Note</th>
                                <th>Distance</th>
                                <th>IO</th>
                                <th>Heat</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($events as $event)
                            <tr class="clickable-row" data-event-id="{{ $event->id }}">
                                <td>{{$event->name}}</td>
                                <td>{{$event->typeID}}</td>
                                <td>{{$event->extra}}</td>
                                <td>{{$event->round}}</td>
                                <td>{{$event->ageGroupID}}</td>
                                <td>{{$event->gender}}</td>
                                <td>{{$event->wind}}</td>
                                <td>{{$event->note}}</td>
                                <td>{{$event->distance}}</td>
                                <td>{{$event->io}}</td>
                                <td>{{$event->heat}}</td>
                                <td>{{$event->created_at}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">No Events for this specific Meeting yet......</td>
                            </tr>
                            @endforelse
                            <form id="createEventForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="meetingID" value="{{$meeting->IDSecond}}">
                                <tr>
                                    <td><input type="text" class="form-control" name="name" placeholder="Name"></td>
                                    <td><input type="text" class="form-control" name="typeID" placeholder="Type ID"
                                            required>
                                    </td>
                                    <td><input type="text" class="form-control" name="extra" placeholder="Extra"></td>
                                    <td><input type="text" class="form-control" name="round" placeholder="Round"
                                            required></td>
                                    <td><input type="text" class="form-control" name="ageGroupID"
                                            placeholder="Age Group ID" required></td>
                                    <td><input type="text" class="form-control" name="gender" placeholder="Gender"
                                            required></td>
                                    <td><input type="number" class="form-control" name="wind" placeholder="Wind"></td>
                                    <td><input type="text" class="form-control" name="note" placeholder="Note"></td>
                                    <td><input type="number" class="form-control" name="distance"
                                            placeholder="Distance"></td>
                                    <td><input type="text" class="form-control" name="io" placeholder="I/O" required>
                                    </td>
                                    <td><input type="tnumber" class="form-control" name="heat" placeholder="Heat"></td>
                                    <td><button type="submit" class="btn btn-primary"
                                            id="createEventButton">Create</button></td>
                                </tr>
                            </form>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Create live event --}}
<script>
    $(document).ready(function() {
        $('#createEventButton').click(function(event) {
            event.preventDefault(); // Prevent form submission and page reload
            
            var formData = new FormData($('#createEventForm')[0]);
            $.ajax({
                url: '/event_create',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Assuming the server responds with the newly created event data in JSON format
                    var newEvent = response.event;

                    // Append the new event to the table
                    var newRow = $('<tr class="clickable-row" onclick="window.location.href = \'/event/' + newEvent.id + '/results\'">');
                    newRow.append('<td>' + newEvent.name + '</td>');
                    newRow.append('<td>' + newEvent.typeID + '</td>');
                    newRow.append('<td>' + newEvent.extra + '</td>');
                    newRow.append('<td>' + newEvent.round + '</td>');
                    newRow.append('<td>' + newEvent.ageGroupID + '</td>');
                    newRow.append('<td>' + newEvent.gender + '</td>');
                    newRow.append('<td>' + newEvent.wind  + '</td>');
                    newRow.append('<td>' + newEvent.note + '</td>');
                    newRow.append('<td>' + newEvent.distance +  '</td>');
                    newRow.append('<td>' + newEvent.io + '</td>');
                    newRow.append('<td>' + newEvent.heat + '</td>');
                    newRow.append('<td>' + newEvent.created_at + '</td>');

                    // Insert the new row before the form
                    $('#events-table tbody tr:last-child').prev().before(newRow);


                    // Clear the form fields
                    $('#createEventForm')[0].reset();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>

@endsection