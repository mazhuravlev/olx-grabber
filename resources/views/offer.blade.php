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
                {{ $phone->id }}
                @if($phone->offers()->count() > 1)
                    <a href="/phone/{{ $phone->id }}">еще {{ $phone->offers()->count() - 1 }}</a>
                @endif
            </li>
        @endforeach
    </ul>
    </p>
    <pre>
        {{ print_r($offer->toArray()) }}
    </pre>
@endsection