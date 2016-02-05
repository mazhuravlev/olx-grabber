@extends('main')
@section('content')
    <h3>{{ $phone->id }}</h3>
    <ul>
        @foreach($phone->offers as $offer)
            <li data-id="{{ $offer->id }}" title="{{ $offer->description }}">
                <a href="/offer/{{ $offer->id }}">
                    {{ $offer->title }}
                </a>
                {{ $offer->price_string }}
                @foreach($offer->phones as $phone)
                    {{ $phone }}
                @endforeach
            </li>
        @endforeach
    </ul>
@endsection