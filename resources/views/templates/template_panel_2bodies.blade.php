@extends('layouts.app')

@section('content')
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                @yield('panel-title-1')
            </div>
            <div class="panel-body">
                @yield('panel-body-1')
            </div>
        </div>
        @yield('button-back')
        <br><br>
        <div class="panel panel-default">
            <div class="panel-heading">
                @yield('panel-title-2')
            </div>
            <div class="panel-body">
                @yield('panel-body-2')
            </div>
        </div>
    </div>
@stop