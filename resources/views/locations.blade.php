@extends('main')
@section('content')
    <style>
        table {
            border-collapse: collapse;
        }

        table td, table th {
            border: 1px solid black;
        }
    </style>
    <table>
        <thead>
        <tr>
            <th>count</th>
            <th>location</th>
            <th>region</th>
        </tr>
        </thead>
        <tbody>
        @foreach($locations as $location)
            <tr>
                <td>{{ $location->offers()->count() }}</td>
                <td>{{ $location->location }}</td>
                <td>
                    <select data-behavior="update-location" data-id="{{ $location->id }}">
                        @foreach($regions as $region)
                            <option></option>
                            <option
                                    @if($region === $location->region) selected @endif
                            >{{ $region }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
    <script>
        $('select[data-behavior="update-location"]').change(function () {
            var $this = $(this);
            $.ajax({
                method: 'post',
                url: '/rest/location/' + $this.data('id'),
                data: {
                    "region": $this.val(),
                    "_method": 'put',
                }
            });
        });
    </script>
@endsection