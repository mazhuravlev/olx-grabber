@extends('main')
@section('content')
    <ul>
        @foreach($locations as $location)
            <li data-id="{{ $location->id }}">
                {{ $location->location }}
            </li>
        @endforeach
    </ul>
@endsection