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
            <th>id</th>
            <th>count</th>
            <th>location</th>
            <th>region</th>
        </tr>
        </thead>
        <tbody>
        @foreach($locations as $location)
            <tr>
                <td>{{ $location->id }}</td>
                <td>
                    {{--{{ $location->offers()->count() }}--}}
                </td>
                <td>{{ $location->location }}</td>
                <td>
                    <select data-behavior="update-location" data-id="{{ $location->id }}">
                        <option></option>
                        @foreach($regions as $region)
                            <option
                                    @if($region->id === $location->region) selected @endif
                            >{{ $region->id }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
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