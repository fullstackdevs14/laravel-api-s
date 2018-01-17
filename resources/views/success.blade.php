@extends('layouts.app')

@section('content')
    <div class="col-md-9">
        @if (Session::has('message'))
            <div class="panel panel-success">
                <div class="panel-heading">
                    Opération éffectuée.
                    @else
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                Ouuuuuuups ! Il semble y avoir une erreur ...
                                @endif
                            </div>

                            <div class="panel-body">
                                @if (Session::has('message'))

                                    {{ Session::get('message') }}

                                @elseif (Session::has('error'))

                                    {{ Session::get('error') }}

                                @else

                                    <p>Action éffectuée :)</p>

                                @endif

                            </div>
                        </div>
                </div>
@endsection
