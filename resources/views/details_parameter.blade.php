@extends('main')
@section('content')
    @if(!$detailsParameter->is_integer_field)
        <table>
            <thead>
            <tr>
                <th>value</th>
                <th>export value</th>
            </tr>
            </thead>
            <tbody>
            @foreach($detailsValues as $detailsValue)
                <tr data-details-parameter-id="{{ $detailsParameter->id }}" data-id="{{ $detailsValue->id }}">
                    <td>
                        {{ $detailsValue->value }}
                    </td>
                    <td>
                        <input type="text" class="export-value" value="{{ $detailsValue->export_value }}">
                    </td>
                    <td>
                        <button class="save">save</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>{{ $detailsParameter->parameter }} is an integer field</p>
    @endif
    <script>
        $('button.save').click(function () {
            var $row = $(this).parents('tr');
            $.ajax({
                method: 'post',
                url: [
                    '/rest/details_parameter',
                    $row.data('details-parameter-id'),
                    'details_value',
                    $row.data('id')
                ].join('/'),
                data: {
                    "export_value": $row.find('.export-value').val(),
                    "_method": 'put'
                }
            });
        });
    </script>
@endsection