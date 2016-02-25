@extends('main')
@section('content')
    <style>
        .action {
            margin: 20px;
        }
    </style>

    <div class="action">
        <form action="/log/truncate" method="post">
            <input type="hidden" name="file" value="{{ $file }}">
            <button type="submit">Truncate log to 0 bytes!</button>
        </form>
    </div>
    <div class="action">
        <form method="post">
            <label for="lines-count">Lines count</label>
            <input id="lines-count" name="lines" type="number" value="100">
            <label for="file">File:</label>
            <select id="file" name="file">
                @foreach($files as $_file)
                    <option @if($_file['file'] === $file) selected @endif value="{{ $_file['file'] }}">
                        {{ $_file['file'] }}: {{ $_file['size'] }}
                    </option>
                @endforeach
            </select>
            <button type="submit">View log!</button>
        </form>
    </div>
    <pre>
       {{ $data }}
   </pre>
@endsection