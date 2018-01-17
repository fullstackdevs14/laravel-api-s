@extends('templates.template_panel')

@section('panel-title')
    List des flux RSS suivis :
@endsection

@section('panel-body')

    @foreach($posts as $post)

        @component('templates.template_panel_inside')
            @slot('title')
                {{ $post['title'] }}
            @endslot
            <!-- Code provenant de l'exterieur, mais les balises sont extraite dans le contrôleur. -->
            {!! Html::ListInfo('Date de publication', $post['pubDate']) !!}
            {!! Html::ListInfo('Description', $post['description']) !!}
            {!! Html::ListInfo('Lien', $post['link']) !!}
            <a href="{{$post['link']}}" target="_blank">Lien vers l'article</a>

            {!! Html::RouteWithIconBlank('fb_post', 'Aller au groupe FB', null, 'btn-default pull-right', 'arrow-right') !!}

        @endcomponent

    @endforeach

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection

@section('script')

@endsection