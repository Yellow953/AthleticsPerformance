@extends('layouts.app')

@section('content')

<div class="container">
    <a href="/competitors" class="mb-3">
        <h3 class="text-white">
            < Back</h3>
    </a>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Update Competitor</strong>
            </div>
            <div class="card-body card-block">
                <form id="update_competitor_form" action="/competitor/{{$competitor->id}}/update" method="post"
                    enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="name" class=" form-control-label">Name*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="name" name="name" value="{{$competitor->name}}"
                                        class="form-control" selected>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="athleteID" class="form-control-label">Athlete</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <div class="input-group">
                                        <input type="text" id="athleteName" class="form-control"
                                            value="{{$competitor->athleteID}}" autocomplete="off">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="clearButton">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                    <path
                                                        d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <ul class="combobox-dropdown">
                                        @foreach ($athletes as $athlete)
                                        <li data-value="{{$athlete->ID}}"
                                            data-name="{{$athlete->firstName}} {{$athlete->middleName ? $athlete->middleName . ' ' : ''}}{{$athlete->lastName}}"
                                            data-gender="{{$athlete->gender}}">
                                            {{$athlete->firstName}}
                                            @if ($athlete->middleName)
                                            ({{$athlete->middleName}})
                                            @endif {{$athlete->lastName}}
                                        </li>
                                        @endforeach
                                    </ul>
                                    <input type="hidden" id="athleteID" name="athleteID"
                                        value="{{$competitor->athleteID}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="year" class=" form-control-label">Year*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="year" name="year"
                                        value="{{$competitor->year ?? Helper::get_current_year()}}" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="gender" class=" form-control-label">Gender*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select id="gender" name="gender" class="form-control" required>
                                        @foreach ($genders as $gender)
                                        <option value="{{$gender->gender}}" {{$competitor->gender == $gender->gender ?
                                            'selected' : ''}}>{{$gender->gender}}</option>
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
                                    <label for="teamID" class=" form-control-label">Team*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select id="teamID" name="teamID" class="form-control" required>
                                        @foreach ($teams as $team)
                                        <option value="{{$team->ID}}" {{$competitor->teamID == $team->ID ?
                                            'selected' : ''}}>{{$team->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="ageGroupID" class=" form-control-label">Age Group*</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select id="ageGroupID" name="ageGroupID" class="form-control" required>
                                        @foreach ($age_groups as $age_group)
                                        <option value="{{$age_group->ID}}" {{$competitor->ageGroupID == $age_group->ID ?
                                            'selected' : ''}}>{{$age_group->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="offset-9 col-3">
                            <button type="submit" class="btn btn-primary">Update Competitor</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Submission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                The name doesn't match the selected athlete's name. Do you want to continue?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSubmission">Continue</button>
            </div>
        </div>
    </div>
</div>

{{-- Confirm Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const submitButton = document.getElementById('submit-button');
    const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    const confirmSubmissionButton = document.getElementById('confirmSubmission');

    submitButton.addEventListener('click', function (event) {
        event.preventDefault();
        validateNameAndShowModal();
    });

    confirmSubmissionButton.addEventListener('click', function () {
        confirmationModal.hide(); // Hide the modal
        submitForm(); // Submit the form
    });

    function validateNameAndShowModal() {
        const athleteInput = document.getElementById('athleteName'); // Input for athlete name
        const nameInput = document.getElementById('name');
        
        if (athleteInput.value) {
            const [firstName, lastName] = athleteInput.value.split(' ');
            if (nameInput.value !== `${firstName} ${lastName}`) {
                confirmationModal.show(); // Show the modal
            } else {
                submitForm(); // Name matches, submit the form
            }
        } else {
            // No athlete selected, submit the form
            submitForm()
        }
    }

    function submitForm() {
        // Submit the form
        document.querySelector('#update_competitor_form').submit();
    }
});
</script>

{{-- ComboBox --}}
<script>
    const comboboxInput = document.getElementById('athleteName');
    const comboboxDropdown = document.querySelector('.combobox-dropdown');
    const hiddenInput = document.getElementById('athleteID');
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
        const inputValue = comboboxInput.value;
        if (inputValue.length >= 4) {
            filterDropdownOptions();
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

    comboboxDropdown.addEventListener('click', function (event) {
        if (event.target.tagName === 'LI') {
            const selectedValue = event.target.getAttribute('data-value');
            const selectedText = event.target.textContent.trim();
            const trimmedText = selectedText.replace(/\s+/g, ' ').trim(); // Replace multiple spaces with a single space and then trim

            // Update the input fields with athlete's information
            comboboxInput.value = trimmedText;
            hiddenInput.value = selectedValue;

            // Automatically fill the name and gender fields
            const selectedAthlete = event.target;
            const name = selectedAthlete.getAttribute('data-name');
            const gender = selectedAthlete.getAttribute('data-gender');

            document.getElementById('name').value = name;
            document.getElementById('gender').value = gender;

            comboboxDropdown.style.display = 'none';
        }
    });
</script>

@endsection