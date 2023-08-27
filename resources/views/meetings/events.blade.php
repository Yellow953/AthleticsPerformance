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

    <div id="eventResults"></div>
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

<!-- Add a click event handler to each event row -->
<script>
    $(document).ready(function() {
        $('.clickable-row').click(function() {
            var eventId = $(this).data('event-id');

            // Clear previous event results
            $('#eventResults').empty();

            // Send AJAX request to fetch event results
            $.ajax({
                url: '/event/' + eventId + '/get_results',
                method: 'GET',
                success: function(response) {
                    var results = response.results;
                    var event = response.event;
                    var competitors = response.competitors;

                    // Create a new card to display event results
                    var row = $('<div class="row">');
                    var col_md_12 = $('<div class="col-md-12">');
                    var card = $('<div class="card px-4 py-3">');

                    // Create a table to display event results
                    var table = $('<table class="results-table mt-3 w-100 mx-2" id="results-table" border="1">');
                    var tableHead = $('<thead>');
                    var tableBody = $('<tbody>');

                    // table headers
                    tableHead.append(
                        `<tr>
                            <th>Competitor ID</th>
                            <th>Position</th>
                            <th>Result</th>
                            <th>Points</th>
                            <th>Result Value</th>
                            <th>Record Status</th>
                            <th>Wind</th>
                            <th>Note</th>
                            <th>Heat</th>
                            <th>Is Hand</th>
                            <th>Is Active</th>
                            <th>Date</th>
                        </tr>`
                    );

                    results.forEach(result => {
                        tableBody.append(
                            `<tr>
                                <td>${result.competitorID}</td>
                                <td>${result.position}</td>
                                <td>${result.result}</td>
                                <td>${result.points}</td>
                                <td>${result.resultValue}</td>
                                <td>${result.recordStatus}</td>
                                <td>${result.wind}</td>
                                <td>${result.note}</td>
                                <td>${result.heat}</td>
                                <td>${result.isHand}</td>
                                <td>${result.isActive}</td>
                                <td>${result.created_at}</td>
                            </tr>`
                        );
                    });

                    resultForm = 
                    `
                        <tr><form id="createResultForm" enctype="multipart/form-data">
                            <input type="hidden" name="eventID" value="${eventId}">
                                    <td>
                                        <div class="input-group">
                                            <input type="text" id="competitor" class="form-control"
                                                placeholder="Competitor">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="clearButton">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                        <path
                                                            d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <ul class="combobox-dropdown" id="competitors-combobox">
                                            `;
                                            
                                            competitors.forEach(competitor => {
                                                resultForm += `<li data-value="${competitor.ID}">${competitor.name}</li>`;
                                            });
                                            
                                            resultForm += `
                                        </ul>
                                        <input type="hidden" id="competitorID" name="competitorID">
                                    </td>
                                    <td><input type="number" class="form-control" name="position"
                                            placeholder="Position">
                                    </td>
                                    <td><input type="number" class="form-control" name="result" placeholder="Result">
                                    </td>
                                    <td><input type="number" class="form-control" name="points" placeholder="Points">
                                    </td>
                                    <td><input type="number" class="form-control" name="resultValue"
                                            placeholder="Result Value" required></td>
                                    <td><input type="text" class="form-control" name="recordStatus"
                                            placeholder="Record Status"></td>
                                    <td><input type="number" class="form-control" name="wind" placeholder="Wind"></td>
                                    <td><input type="text" class="form-control" name="note" placeholder="Note"></td>
                                    <td><input type="number" class="form-control" name="heat" placeholder="Heat"></td>
                                    <td><input type="checkbox" class="form-control" name="isHand"></td>
                                    <td><input type="checkbox" class="form-control" name="isActive"></td>
                                    <td><button type="submit" class="btn btn-primary"
                                            id="createResultButton">Create</button></td>
                            
                        </form></tr>
                    `;
                    tableBody.append(resultForm);
                    
                    // Append the table head and body to the table
                    table.append(tableHead);
                    table.append(tableBody);

                    // Append the table to the card body
                    card.append('<h4 class="mb-1">Event: ' + event.name + '</h4> <hr>');
                    card.append('<h4>Results:</h4>');
                    card.append(table);

                    col_md_12.append(card);
                    row.append(col_md_12);

                    // Append the card to the eventResults div
                    $('#eventResults').append(row);

                    setupComboBox();

                    createResult();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });

    function setupComboBox() {
        const comboboxInput = document.getElementById('competitor');
        const comboboxDropdown = document.querySelector('.combobox-dropdown');
        const hiddenInput = document.getElementById('competitorID');
        const dropdownOptions = comboboxDropdown.querySelectorAll('li[data-value]');

        const clearButton = document.getElementById('clearButton');
        clearButton.addEventListener('click', function () {
            comboboxInput.value = ''; // Clear the input
            hiddenInput.value = ''; // Clear the hidden input
            comboboxDropdown.style.display = 'none'; // Hide the dropdown
        });

        comboboxInput.addEventListener('focus', function () {
            comboboxDropdown.style.display = 'block';
            filterDropdownOptions();
        });

        comboboxInput.addEventListener('blur', function () {
            setTimeout(() => {
                comboboxDropdown.style.display = 'none';
            }, 200);
        });

        comboboxInput.addEventListener('input', function () {
            filterDropdownOptions();
        });

        comboboxDropdown.addEventListener('click', function (event) {
            if (event.target.tagName === 'LI') {
                const selectedValue = event.target.getAttribute('data-value');
                const selectedText = event.target.textContent.trim();

                comboboxInput.value = selectedText;
                hiddenInput.value = selectedValue;

                comboboxDropdown.style.display = 'none';
            }
        });

        function filterDropdownOptions() {
            const searchTerm = comboboxInput.value.toLowerCase();
            for (const option of dropdownOptions) {
                const optionText = option.textContent.toLowerCase();
                if (optionText.includes(searchTerm)) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            }
        }
    }

    function createResult() {
        $(document).ready(function() {
            $('#createResultButton').click(function(event) {
                event.preventDefault(); // Prevent form submission and page reload
                            
                var formData = new FormData($('#createResultForm')[0]);
                $.ajax({
                    url: '/result_create',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var newResult = response.result;

                        // Append the new result to the table
                        var newRow = $('<tr>');
                        newRow.append('<td>' + newResult.competitorID + '</td>');
                        newRow.append('<td>' + newResult.position + '</td>');
                        newRow.append('<td>' + newResult.result + '</td>');
                        newRow.append('<td>' + newResult.points + '</td>');
                        newRow.append('<td>' + newResult.resultValue + '</td>');
                        newRow.append('<td>' + newResult.recordStatus + '</td>');
                        newRow.append('<td>' + newResult.wind  + '</td>');
                        newRow.append('<td>' + newResult.note + '</td>');
                        newRow.append('<td>' + newResult.heat +  '</td>');
                        newRow.append('<td>' + newResult.isHand + '</td>');
                        newRow.append('<td>' + newResult.isActive + '</td>');
                        newRow.append('<td>' + newResult.created_at + '</td>');

                        // Insert the new row before the form
                        $('#results-table tbody tr:last-child').prev().before(newRow);

                        // Clear the form fields
                        $('#createResultForm')[0].reset();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        });
    }
</script>

@endsection