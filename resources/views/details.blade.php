@extends('main')
@section('content')
    <table>
        <thead>
        <tr>
            <th>parameter</th>
            <th>export field</th>
            <th>is integer</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($detailsParameters as $detailsParameter)
            <tr data-id="{{ $detailsParameter->id }}">
                <td>
                    <a href="/details/{{ $detailsParameter->id }}">
                        {{ $detailsParameter->parameter }}
                    </a>
                </td>
                <td>
                    <input type="text" class="export-property" value="{{ $detailsParameter->export_property }}">
                </td>
                <td style="text-align: center">
                    <input type="checkbox"
                           @if($detailsParameter->is_integer_field)
                           checked
                            @endif
                    >
                </td>
                <td>
                    <button class="save">save</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script>
        $('button.save').click(function () {
            var $row = $(this).parents('tr');
            $.ajax({
                method: 'post',
                url: '/rest/details_parameter/' + $row.data('id'),
                data: {
                    "export_property": $row.find('.export-property').val(),
                    "is_integer_field": $row.find('input[type="checkbox"]').prop('checked'),
                    "_method": 'put'
                }
            });
        });
    </script>
@endsection