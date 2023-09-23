@extends('layouts.app')

@section('content')

<div class="container">
    <a href="/results" class="mb-3">
        <h3 class="text-white">
            < Back</h3>
    </a>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Create Result</strong>
            </div>
            <div class="card-body card-block">
                <form action="/result/create" method="post" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="eventID" class=" form-control-label">Event</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="eventID" id="eventID" class="form-control">
                                        @foreach ($events as $event)
                                        <option value="{{$event->id}}">{{$event->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="competitorID" class=" form-control-label">Competitor</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="competitorID" id="competitorID" class="form-control">
                                        @foreach ($competitors as $competitor)
                                        <option value="{{$competitor->id}}">{{$competitor->name}}</option>
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
                                    <label for="position" class=" form-control-label">Position</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="position" name="position" step="1" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="result" class=" form-control-label">Result</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="result" name="result" step="0.01" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="points" class=" form-control-label">Points</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="points" name="points" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="resultValue" class=" form-control-label">Result Value</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="resultValue" name="resultValue" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="wind" class=" form-control-label">Wind</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="wind" name="wind" placeholder="Wind" class="form-control"
                                        step="0.1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="recordStatus" class=" form-control-label">Record Status</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="recordStatus" name="recordStatus" placeholder="Record Status"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="heat" class=" form-control-label">Heat</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="number" id="heat" name="heat" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-4">
                        <div class="col-md-3">
                            <div class="checkbox">
                                <label for="isHand" class="form-check-label ">
                                    <input type="checkbox" id="isHand" name="isHand" class="form-check-input">Hand
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="checkbox">
                                <label for="isActive" class="form-check-label ">
                                    <input type="checkbox" id="isActive" name="isActive" class="form-check-input">Active
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="offset-9 col-3">
                            <button type="submit" class="btn btn-primary">Create Result</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection