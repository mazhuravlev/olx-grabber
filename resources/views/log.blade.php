@extends('main')
@section('content')
    <style>
        .action {
            margin: 20px;
        }
    </style>

    <div class="action">
        <form action="/log/truncate" method="post">
            <button type="submit">Truncate log to 0 bytes!</button>
        </form>
    </div>
    <div class="action">
        <form>
            <label for="lines-count">Lines count</label>
            <input id="lines-count" name="lines" type="number" value="100">
            <button type="submit">View log!</button>
        </form>
    </div>
    <pre>
       {{ $data }}
   </pre>
@endsection