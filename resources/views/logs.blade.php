@extends('main')
@section('content')
    <style>
        .action {
            margin: 20px;
        }
    </style>

    <div class="action">
        <form action="/log" method="post">
            <label for="lines-count">Lines count</label>
            <input id="lines-count" name="lines" type="number" value="100">
            <label for="file">File:</label>
            <select id="file" name="file">
                @foreach($files as $file)
                    <option>{{ $file }}</option>
                @endforeach
            </select>
            <button type="submit">View!</button>
        </form>
    </div>
@endsection