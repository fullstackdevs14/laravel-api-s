@extends('templates.template_panel')

@section('head')
    {!! Charts::assets() !!}
@endsection

@section('panel-title')
    Tableau de bord
@endsection

@section('panel-body')

    <div class="row">
        <div class="col-md-6">
            {!! $chart1->render() !!}
        </div>
        <div class="col-md-6">
            {!! $chart2->render() !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            {!! $chart3->render() !!}
        </div>
        <div class="col-md-6">
            {!! $chart4->render() !!}
        </div>
    </div>

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection