@extends('main')
@section('content')
    <p>
        Ссылка на оригинал:
        <a href="{{ $offer->href }}">
            {{ $offer->title }}
        </a>
    </p>
    <form action="/offer/{{ $offer->id }}/export" method="post">
        <button type="submit"
                @if(!$offer->location)
                disabled
                @endif
        >Экспортировать
            @if($offer->location)
                : {{ $offer->location->region }}
            @endif
        </button>
    </form>
    <div>
        Местоположение:
        @if($offer->location)
            {{ $offer->location->location }} ({{ $offer->location->region }})
        @else
            не определено
        @endif
        <div>
            <form method="post" action="/offer/{{$offer->id}}/location">
                <label for="change-location">Сменить местоположение</label>
                <select id="change-location" name="location_id">
                    @foreach($locations as $location)
                        <option value="{{$location->id}}">{{$location->location}}: {{$location->region}}</option>
                    @endforeach
                </select>
                <button type="submit">Сменить</button>
            </form>
        </div>
    </div>
    <p>
        {{ $offer->description }}
    </p>
    <p>
        @if($offer->phones()->count() > 0)
            Телефоны:
    <ul>
        @foreach($offer->phones()->get() as $phone)
            <li>
                {{ $phone->id }}
                @if($phone->offers()->count() > 1)
                    <a href="/phone/{{ $phone->id }}">еще {{ $phone->offers()->count() - 1 }}</a>
                @endif
            </li>
        @endforeach
    </ul>
    @endif
    @if($offer->invalidPhones()->count() >  0)
        Нераспознанные телефоны:
        <ul>
            @foreach($offer->invalidPhones()->get() as $invalidPhone)
                <li>{{ $invalidPhone->phone }}</li>
            @endforeach
        </ul>
    @endif

    @foreach($offer->photos as $photo)
        <img src="{{ $photo->url }}">
    @endforeach
    <pre>
        {{ print_r($offer->toArray()) }}
    </pre>
    <script>
        $(function () {
            $('#change-location').select2();
        })
    </script>
@endsection