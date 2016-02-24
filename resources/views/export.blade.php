@extends('main')
@section('content')
    <form method="post">
        <ul>
            @foreach($regions as $region)
                <li>
                    <label for="region_{{ $region->id }}">{{ $region->id }}</label>
                    <input id="region_{{ $region->id }}" type="checkbox" name="regions[]" value="{{ $region->id }}">
                </li>
            @endforeach
        </ul>
        <label for="day-count">export offers X last days</label>
        <p><input id="day-count" name="day_count" type="number" value="1" step="any"></p>
        <button type="submit">export</button>
    </form>
@endsection