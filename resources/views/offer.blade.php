@extends('main')
@section('content')
    <p>
        <a href="{{ $offer->href }}">
            {{ $offer->title }}
        </a>
    </p>
    <p>
        Местоположение:
        @if($offer->location)
            {{ $offer->location->location }} ({{ $offer->location->region }})
        @else
            не определено
        @endif
    </p>
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
@endsection