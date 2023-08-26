@extends('layouts.app')

@section('content')

<div class="container">
    <a href="/events" class="mb-3">
        <h3 class="text-white">
            < Back</h3>
    </a>

    <div class="row">
        <div class="col-md-12">
            <div class="card bg-primary">
                <div class="meeting-info mx-4 my-3">
                    <h2>Event</h2>
                    <div class="row mt-3 text-dark">
                        <div class="col-md-2">
                            <div><span class="text-white">Name:</span> {{$event->name ?? 'NULL'}}</div>
                        </div>
                        <div class="col-md-2">
                            <div><span class="text-white">MeetingID:</span> {{$event->meetingID}}</div>
                            <div><span class="text-white">Age Group:</span> {{$event->ageGroupID}}</div>
                            <div><span class="text-white">Type:</span> {{$event->typeID}}</div>
                        </div>
                        <div class="col-md-2">
                            <div><span class="text-white">Create Date:</span> {{$event->created_at}}</div>
                        </div>
                        <div class="col-md-3">
                            <div><span class="text-white">Gender:</span> {{$event->gender ?? 'NULL'}}</div>
                            <div><span class="text-white">Wind:</span> {{$event->wind ?? 'NULL'}}</div>
                            <div><span class="text-white">Note:</span> {{$event->note ?? 'NULL'}}</div>
                            <div><span class="text-white">Extra:</span> {{$event->extra ?? 'NULL'}}</div>
                        </div>
                        <div class="col-md-3">
                            <div><span class="text-white">Round:</span> {{$event->round ?? 'NULL'}}</div>
                            <div><span class="text-white">Distance:</span> {{$event->distance ?? 'NULL'}}</div>
                            <div><span class="text-white">IO:</span> {{$event->io ?? 'NULL'}}</div>
                            <div><span class="text-white">Heat:</span> {{$event->heat ?? 'NULL'}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="results mx-4 my-3">
                    <h3>Results</h3>

                    <table class="results-table mt-3 w-100 mx-2" id="results-table" border="1">
                        <thead>
                            <tr>
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
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($results as $result)
                            <tr>
                                <td>{{$result->competitorID}}</td>
                                <td>{{$result->position}}</td>
                                <td>{{$result->result}}</td>
                                <td>{{$result->points}}</td>
                                <td>{{$result->resultValue}}</td>
                                <td>{{$result->recordStatus}}</td>
                                <td>{{$result->wind}}</td>
                                <td>{{$result->note}}</td>
                                <td>{{$result->heat}}</td>
                                <td>{{ $result->isHand != 0 ? 'true' : 'false' }}</td>
                                <td>{{ $result->isActive != 0 ? 'true' : 'false' }}</td>
                                <td>{{$result->created_at}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">No Results for this specific Event yet......</td>
                            </tr>
                            @endforelse
                            <form id="createResultForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="eventID" value="{{$event->id}}">
                                <tr>
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
                                            @foreach ($competitors as $competitor)
                                            <li data-value="{{$competitor->ID}}">{{$competitor->name}}</li>
                                            @endforeach
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
                                </tr>
                            </form>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ComboBox --}}
<script>
    const comboboxInput = document.getElementById('competitor');
    const comboboxDropdown = document.querySelector('.combobox-dropdown');
    const hiddenInput = document.getElementById('competitorID');
    const dropdownOptions = comboboxDropdown.querySelectorAll('li[data-value]');

    const clearButton = document.getElementById('clearButton');
    // Attach an event listener to the clear button
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
</script>

{{-- Create live result --}}
<script>
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
                    // Assuming the server responds with the newly created result data in JSON format
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
</script>

@endsection