@extends('main')
@section('content')
    <ul>
        @foreach($locations as $location)
            <li data-id="{{ $location->id }}">
                {{ $location->location }}: {{ $location->offers()->count() }}
            </li>
        @endforeach
    </ul>
@endsection