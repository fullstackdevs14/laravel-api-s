@extends('layouts.app')

@section('content')


    <div class="col-md-9">
        @if(session()->has('ok'))
            <div class="alert alert-success alert-dismissible">{{ session('ok') }}</div>
        @endif

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Liste des leads : <strong style="color: #088A9B">{{ $count }} addresses email dans la BDD.</strong></h3>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($records as $record)
                        <tr>
                            <td style="vertical-align: middle">{{ $record[0] }}</td>
                            <td style="vertical-align: middle" class="text-default">{{ $record[1] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <a href="javascript:history.back()" class="btn btn-default">
                <span class="glyphicon glyphicon-circle-arrow-left"></span> Retour
            </a>
    </div>
@endsection