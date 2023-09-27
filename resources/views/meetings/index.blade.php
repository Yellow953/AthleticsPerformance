@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <h3 class="title-5 m-b-35 text-primary">Meetings</h3>
            <div class="table-data__tool">
                <div class="table-data__tool-left">
                    <form action="/meetings" method="get" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-8">
                                <input type="text" name="search" id="search" class="form-control"
                                    value="{{request()->query('search')}}" placeholder="Search By Name, Short Name ...">
                            </div>
                            <div class="col-2">
                                <button class="btn btn-primary mx-1" type="submit">
                                    <i class="zmdi zmdi-search mx-1"></i>search</button>
                            </div>
                        </div>
                    </form>


                </div>
                <div class="table-data__tool-right">
                    <a class="btn btn-primary" href="/meeting/new">
                        <i class="zmdi zmdi-plus mx-1"></i>Add Meeting</a>
                    <a href="/meetings/export" class="btn btn-secondary mx-1">
                        <div class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-filetype-xlsx my-auto mr-1" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M14 4.5V11h-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM7.86 14.841a1.13 1.13 0 0 0 .401.823c.13.108.29.192.479.252.19.061.411.091.665.091.338 0 .624-.053.858-.158.237-.105.416-.252.54-.44a1.17 1.17 0 0 0 .187-.656c0-.224-.045-.41-.135-.56a1.002 1.002 0 0 0-.375-.357 2.028 2.028 0 0 0-.565-.21l-.621-.144a.97.97 0 0 1-.405-.176.37.37 0 0 1-.143-.299c0-.156.061-.284.184-.384.125-.101.296-.152.513-.152.143 0 .266.023.37.068a.624.624 0 0 1 .245.181.56.56 0 0 1 .12.258h.75a1.093 1.093 0 0 0-.199-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.552.05-.777.15-.224.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.123.524.082.149.199.27.351.367.153.095.332.167.54.213l.618.144c.207.049.36.113.462.193a.387.387 0 0 1 .153.326.512.512 0 0 1-.085.29.558.558 0 0 1-.255.193c-.111.047-.25.07-.413.07-.117 0-.224-.013-.32-.04a.837.837 0 0 1-.249-.115.578.578 0 0 1-.255-.384h-.764Zm-3.726-2.909h.893l-1.274 2.007 1.254 1.992h-.908l-.85-1.415h-.035l-.853 1.415H1.5l1.24-2.016-1.228-1.983h.931l.832 1.438h.036l.823-1.438Zm1.923 3.325h1.697v.674H5.266v-3.999h.791v3.325Zm7.636-3.325h.893l-1.274 2.007 1.254 1.992h-.908l-.85-1.415h-.035l-.853 1.415h-.861l1.24-2.016-1.228-1.983h.931l.832 1.438h.036l.823-1.438Z" />
                            </svg>
                            <span class="my-auto">Export</span>
                        </div>
                    </a>
                    @if(auth()->user()->role == 'admin')
                    <a href="/meetings/upload" class="btn btn-success mx-1">
                        <div class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-database-fill my-auto mr-1" viewBox="0 0 16 16">
                                <path
                                    d="M3.904 1.777C4.978 1.289 6.427 1 8 1s3.022.289 4.096.777C13.125 2.245 14 2.993 14 4s-.875 1.755-1.904 2.223C11.022 6.711 9.573 7 8 7s-3.022-.289-4.096-.777C2.875 5.755 2 5.007 2 4s.875-1.755 1.904-2.223Z" />
                                <path
                                    d="M2 6.161V7c0 1.007.875 1.755 1.904 2.223C4.978 9.71 6.427 10 8 10s3.022-.289 4.096-.777C13.125 8.755 14 8.007 14 7v-.839c-.457.432-1.004.751-1.49.972C11.278 7.693 9.682 8 8 8s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972Z" />
                                <path
                                    d="M2 9.161V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13s3.022-.289 4.096-.777C13.125 11.755 14 11.007 14 10v-.839c-.457.432-1.004.751-1.49.972-1.232.56-2.828.867-4.51.867s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972Z" />
                                <path
                                    d="M2 12.161V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16s3.022-.289 4.096-.777C13.125 14.755 14 14.007 14 13v-.839c-.457.432-1.004.751-1.49.972-1.232.56-2.828.867-4.51.867s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972Z" />
                            </svg>
                            <span class="my-auto">Upload</span>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
            <div class="table-responsive table-responsive-data2">
                <table class="table table-data2" id="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Details</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($meetings as $meeting)
                        <tr class="tr-shadow">
                            <td>
                                <div class="row">
                                    <div class="col-3 my-auto">
                                        <img src="{{asset($meeting->image ?? '/assets/images/no_img.png')}}" alt="">
                                    </div>
                                    <div class="col-9 my-auto">
                                        {{ucwords($meeting->shortName)}}<br>
                                        {{ucwords($meeting->name)}}
                                    </div>
                                </div>

                            </td>
                            <td>
                                <span class="block-email m-1">{{$meeting->startDate}}</span>

                                @if ($meeting->endDate)
                                -> <span class="block-email m-1">{{$meeting->endDate}}</span>
                                @endif
                            </td>
                            <td class="text-sm">
                                <small>AgeGroup: {{$meeting->ageGroupID}} <br>
                                    Type: {{$meeting->typeID}} <br>
                                    Venue: {{$meeting->venue}} <br>
                                    Country: {{$meeting->country}} <br>
                                    SubGroup: {{$meeting->subgroup}} <br></small>
                            </td>
                            <td>
                                <div class="table-data-feature">
                                    <a class="item bg-primary d-flex align-items-center justify-content-center"
                                        href="/meeting/{{$meeting->id}}/events" data-toggle="tooltip"
                                        data-placement="top" title="Events">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                                            class="bi bi-cone-striped" viewBox="0 0 16 16">
                                            <path
                                                d="m9.97 4.88.953 3.811C10.159 8.878 9.14 9 8 9c-1.14 0-2.158-.122-2.923-.309L6.03 4.88C6.635 4.957 7.3 5 8 5s1.365-.043 1.97-.120zm-.245-.978L8.97.88C8.718-.13 7.282-.13 7.03.88L6.275 3.9C6.8 3.965 7.382 4 8 4c.618 0 1.2-.036 1.725-.098zm4.396 8.613a.5.5 0 0 1 .037.960l-6 2a.5.5 0 0 1-.316 0l-6-2a.5.5 0 0 1 .037-.960l2.391-.598.565-2.257c.862.212 1.964.339 3.165.339s2.303-.127 3.165-.339l.565 2.257 2.391.598z" />
                                        </svg>
                                    </a>

                                    @if(auth()->user()->role == 'admin')
                                    @if (!$meeting->uploaded)
                                    <a class="item bg-success d-flex align-items-center justify-content-center"
                                        href="/meetings/upload/{{$meeting->id}}" data-toggle="tooltip"
                                        data-placement="top" title="Upload">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                                            class="bi bi-database-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M3.904 1.777C4.978 1.289 6.427 1 8 1s3.022.289 4.096.777C13.125 2.245 14 2.993 14 4s-.875 1.755-1.904 2.223C11.022 6.711 9.573 7 8 7s-3.022-.289-4.096-.777C2.875 5.755 2 5.007 2 4s.875-1.755 1.904-2.223Z" />
                                            <path
                                                d="M2 6.161V7c0 1.007.875 1.755 1.904 2.223C4.978 9.71 6.427 10 8 10s3.022-.289 4.096-.777C13.125 8.755 14 8.007 14 7v-.839c-.457.432-1.004.751-1.49.972C11.278 7.693 9.682 8 8 8s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972Z" />
                                            <path
                                                d="M2 9.161V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13s3.022-.289 4.096-.777C13.125 11.755 14 11.007 14 10v-.839c-.457.432-1.004.751-1.49.972-1.232.56-2.828.867-4.51.867s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972Z" />
                                            <path
                                                d="M2 12.161V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16s3.022-.289 4.096-.777C13.125 14.755 14 14.007 14 13v-.839c-.457.432-1.004.751-1.49.972-1.232.56-2.828.867-4.51.867s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972Z" />
                                        </svg>
                                    </a>
                                    @endif

                                    <a class="item bg-warning" href="/meeting/{{$meeting->id}}/edit"
                                        data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="zmdi zmdi-edit text-dark"></i>
                                    </a>
                                    <form method="GET" action="/meeting/{{$meeting->id}}/destroy">
                                        @csrf
                                        <button class="item bg-danger show_confirm" type="submit" data-toggle="tooltip"
                                            data-placement="top" title="Delete">
                                            <i class="zmdi zmdi-delete text-dark"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr class="spacer"></tr>
                        @empty
                        <tr>
                            <td colspan="4">No Meetings Found ...</td>
                        </tr>
                        @endforelse
                        <tr>
                            <td colspan="4">{{$meetings->appends(['search' => request()->query('search')])->links()}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- END DATA TABLE -->
        </div>
    </div>
</div>

@endsection