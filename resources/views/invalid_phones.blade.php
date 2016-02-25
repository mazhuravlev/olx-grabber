@extends('main')
@section('content')
    <ul>
        @foreach($phones as $phone)
            <li>
                {{ $phone->phone }} <a href="/offer/{{ $phone->offer_id }}">&gt;</a>
            </li>
        @endforeach
    </ul>

@endsection