@extends('layouts.app')

@section('head')
    @yield('head')
@endsection

@section('content')
    <div class="col-md-9">
        @yield('search-bar')
        <div class="panel panel-default">
            <div class="panel-heading">
                @yield('panel-title')
            </div>
            <div class="panel-body">
                @yield('panel-body')
            </div>
        </div>
        @yield('links')
        <br>
        @yield('button-back')
        <br>
        <br>
    </div>
@stop