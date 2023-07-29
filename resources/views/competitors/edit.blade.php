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
                <form action="/competitor/{{$competitor->id}}/update" method="post" enctype="multipart/form-data"
                    class="form-horizontal">
                    @csrf
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="text-input" class=" form-control-label">Text Input</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="text" id="text-input" name="text-input" placeholder="Text"
                                class="form-control">
                            <small class="form-text text-muted">This is a help text</small>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="email-input" class=" form-control-label">Email Input</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="email" id="email-input" name="email-input" placeholder="Enter Email"
                                class="form-control">
                            <small class="help-block form-text">Please enter your email</small>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="password-input" class=" form-control-label">Password</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="password" id="password-input" name="password-input" placeholder="Password"
                                class="form-control">
                            <small class="help-block form-text">Please enter a complex password</small>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="disabled-input" class=" form-control-label">Disabled Input</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="text" id="disabled-input" name="disabled-input" placeholder="Disabled"
                                disabled="" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="textarea-input" class=" form-control-label">Textarea</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <textarea name="textarea-input" id="textarea-input" rows="9" placeholder="Content..."
                                class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="select" class=" form-control-label">Select</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <select name="select" id="select" class="form-control">
                                <option value="0">Please select</option>
                                <option value="1">Option #1</option>
                                <option value="2">Option #2</option>
                                <option value="3">Option #3</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label class=" form-control-label">Radios</label>
                        </div>
                        <div class="col col-md-9">
                            <div class="form-check">
                                <div class="radio">
                                    <label for="radio1" class="form-check-label ">
                                        <input type="radio" id="radio1" name="radios" value="option1"
                                            class="form-check-input">Option 1
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="radio2" class="form-check-label ">
                                        <input type="radio" id="radio2" name="radios" value="option2"
                                            class="form-check-input">Option 2
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="radio3" class="form-check-label ">
                                        <input type="radio" id="radio3" name="radios" value="option3"
                                            class="form-check-input">Option 3
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label class=" form-control-label">Checkboxes</label>
                        </div>
                        <div class="col col-md-9">
                            <div class="form-check">
                                <div class="checkbox">
                                    <label for="checkbox1" class="form-check-label ">
                                        <input type="checkbox" id="checkbox1" name="checkbox1" value="option1"
                                            class="form-check-input">Option 1
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label for="checkbox2" class="form-check-label ">
                                        <input type="checkbox" id="checkbox2" name="checkbox2" value="option2"
                                            class="form-check-input"> Option 2
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label for="checkbox3" class="form-check-label ">
                                        <input type="checkbox" id="checkbox3" name="checkbox3" value="option3"
                                            class="form-check-input"> Option 3
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="file-input" class=" form-control-label">File input</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="file" id="file-input" name="file-input" class="form-control-file">
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

@endsection