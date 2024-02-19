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
                <div class="meeting-info m-3">
                    <h2>Event</h2>
                    <div class="row mt-3 text-dark">
                        <div class="col-md-3">
                            <div><span class="text-white">Name:</span> {{$event->name ?? 'NULL'}}</div>
                        </div>
                        <div class="col-md-3">
                            <div><span class="text-white">Meeting:</span> {{$event->meeting->shortName ?? ''}}</div>
                            <div><span class="text-white">Age Group:</span> {{$event->ageGroup->name}}</div>
                            <div><span class="text-white">Type:</span> {{$event->type->name}}</div>
                        </div>
                        <div class="col-md-3">
                            <div><span class="text-white">Gender:</span> {{$event->gender ?? 'NULL'}}</div>
                            <div><span class="text-white">Note:</span> {{$event->note ?? 'NULL'}}</div>
                            <div><span class="text-white">Extra:</span> {{$event->extra ?? 'NULL'}}</div>
                        </div>
                        <div class="col-md-3">
                            <div><span class="text-white">Round:</span> {{$event->round ?? 'NULL'}}</div>
                            <div><span class="text-white">Distance:</span> {{$event->distance ?? 'NULL'}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="results m-3" style="overflow:auto;">
                    <h3>Results</h3>

                    <div id="error-message" class="alert alert-danger my-4" style="display: none;"></div>

                    <table class="results-table mt-3 w-100 mx-2" id="results-table" border="1">
                        <thead>
                            <tr>
                                <th>Competitor</th>
                                <th>Position</th>
                                <th>Result</th>
                                <th>Wind</th>
                                <th>Note</th>
                                <th>Heat</th>
                                <th>Hand</th>
                                <th>Active</th>
                                <th>Record</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($results as $result)
                            <tr class="clickable-row"
                                onclick="window.location.href = '/results/' + {{$result->id}} + '/edit'">
                                <td>
                                    @if ($result->competitor)
                                    {{ $result->competitor->name }}
                                    @if ($result->competitor->team)
                                    {{ $result->competitor->team->name }}
                                    @endif
                                    @endif
                                </td>
                                <td>{{$result->position}}</td>
                                <td>{{$result->result}}</td>
                                <td>{{$result->wind}}</td>
                                <td>{{$result->note}}</td>
                                <td>{{$result->heat}}</td>
                                <td>{{ $result->isHand != 0 ? 'true' : 'false' }}</td>
                                <td>{{ $result->isActive != 0 ? 'true' : 'false' }}</td>
                                <td class="my-auto text-center">
                                    <a href="/results/{{$result->id}}/new_record"
                                        class="btn btn-primary py-1 px-2">+</a>
                                </td>
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
                                                placeholder="Competitor" autocomplete="off">
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
                                            <li data-value="{{$competitor->id}}">{{$competitor->name}}</li>
                                            @endforeach
                                        </ul>
                                        <input type="hidden" id="competitorID" name="competitorID">
                                    </td>
                                    <td><input type="number" class="form-control" name="position"
                                            placeholder="Position">
                                    </td>
                                    <td><input type="text" class="form-control" name="result" placeholder="Result">
                                    </td>
                                    <td><input type="number" class="form-control" name="wind" placeholder="Wind"
                                            step="0.1"></td>
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

    clearButton.addEventListener('click', function () {
        comboboxInput.value = '';
        hiddenInput.value = '';
        comboboxDropdown.style.display = 'none';
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
            event.preventDefault();
            
            var formData = new FormData($('#createResultForm')[0]);
            $.ajax({
                url: '/events/result_create',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var newResult = response.result;

                    var newRow = $('<tr>');
                    newRow.append('<td>' + newResult.competitorID + '</td>');
                    newRow.append('<td>' + newResult.position + '</td>');
                    newRow.append('<td>' + newResult.result + '</td>');
                    newRow.append('<td>' + newResult.wind  + '</td>');
                    newRow.append('<td>' + newResult.note + '</td>');
                    newRow.append('<td>' + newResult.heat +  '</td>');
                    newRow.append('<td>' + newResult.isHand + '</td>');
                    newRow.append('<td>' + newResult.isActive + '</td>');
                    newRow.append('<td class="my-auto text-center"><a href="/results/' + newResult.id + '/new_record" class="btn btn-primary py-1 px-2">+</a></td>');

                    $('#results-table tbody tr:last-child').prev().before(newRow);

                    $('#createResultForm')[0].reset();

                    $('#error-message').hide();
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