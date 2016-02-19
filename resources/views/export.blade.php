@extends('main')
@section('content')
    <form method="post">
        <ul>
            @foreach($regions as $region)
                <li>
                    <label for="region_{{ $region }}">{{ $region }}</label>
                    <input id="region_{{ $region }}" type="checkbox" name="regions[]" value="{{ $region }}">
                </li>
            @endforeach
        </ul>
        <label for="day-count">export offers X last days</label>
        <p><input id="day-count" name="day_count" type="number" value="1"></p>
        <button type="submit">export</button>
    </form>
@endsection