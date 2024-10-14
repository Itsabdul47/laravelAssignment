@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Categories</h1>

    <div class="mb-4">
        <a href="{{ route('categories.create') }}" class="btn btn-primary">Add Category</a>
    </div>

    <!-- DataTable HTML -->
    <table class="table mt-3" id="categories-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>
@endsection



@section('css')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery first -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> <!-- DataTables after jQuery -->
<script>
    $(document).ready(function () {
        console.log('Initializing DataTable...');
        $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('categories.datatable') }}',
                type: 'GET',
                error: function (xhr, error, thrown) {
                    console.error('Error fetching data:', error);
                    console.log('Response:', xhr.responseText);
                },
                dataSrc: function (json) {
                    console.log('Data fetched:', json);
                    return json.data;
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                {
                    data: 'image', name: 'image', orderable: false, searchable: false, render: function (data, type, full, meta) {
                        return data ? '<img src="/' + data + '" width="50"/>' : 'No Image';
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@stop