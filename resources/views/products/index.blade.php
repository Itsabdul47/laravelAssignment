@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
    <table class="table mt-3" id="products-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Categories</th>
                <th>Images</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('products.getdatatable') }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'price', name: 'price' },
                { data: 'categories', name: 'categories', orderable: false },
                {
                    data: 'image',
                    name: 'image',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, full, meta) {
                        const baseUrl = '{{ asset('') }}'; // Base URL for asset path
                        console.log('Images Data:', data); // Log the data

                        // Check if 'data' is an array (for multiple images) or a string (for a single image)
                        if (Array.isArray(data)) {
                            // Handle multiple images
                            return data.map(function (image) {
                                const imageUrl = baseUrl + image;
                                console.log('Image URL (array):', imageUrl); // Log each image URL
                                return `<img src="${imageUrl}" width="50" height="50" style="object-fit: cover; cursor: pointer;" onclick="showImageModal('${imageUrl}')" />`;
                            }).join(' ');
                        } else if (typeof data === 'string' && data) {
                            // Handle a single image (when 'data' is a string)
                            const imageUrl = baseUrl + data;
                            console.log('Image URL (string):', imageUrl); // Log the image URL
                            return `<img src="${imageUrl}" width="50" height="50" style="object-fit: cover; cursor: pointer;" onclick="showImageModal('${imageUrl}')" />`;
                        } else {
                            // No image
                            return 'No Image';
                        }
                    }
                },

                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'asc']],
        });
    });

    function showImageModal(imageUrl) {

        console.log(imageUrl);
        const modalHtml = `
            <div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Image Preview</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="${imageUrl}" alt="Product Image" class="img-fluid" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>`;

        // Append the modal to the body and display it
        $('body').append(modalHtml);
        $('#imageModal').modal('show');
        $('#imageModal').on('hidden.bs.modal', function () {
            $('#imageModal').remove();
        });
    }
</script>
@stop