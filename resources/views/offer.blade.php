@extends('main')
@section('content')
    <p>
        <a href="{{ $offer->href }}">
            {{ $offer->title }}
        </a>
    </p>
    <p>
        {{ $offer->description }}
    </p>
    <p>
    <ul>
        @foreach($offer->phones()->get() as $phone)
            <li>
                <a href="/phone/{{ $phone->id }}">{{ $phone->id }}</a>
            </li>
        @endforeach
    </ul>
    </p>
    <p>
        {{ var_dump($offer->toArray()) }}
    </p>
@endsection