@extends('main')
@section('content')
    <ul>
        @foreach($phones as $phone)
            <li>
                <a href="/phone/{{ $phone->id }}">{{ $phone->id }}</a> :
                {{ $phone->offers()->count() }}
            </li>
        @endforeach
    </ul>

    {{ $phones->render() }}
@endsection