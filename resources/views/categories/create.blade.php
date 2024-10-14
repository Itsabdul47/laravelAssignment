@extends('adminlte::page')

@section('content')
    <div class="container">
        <h1>Add Category</h1>
        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" id="category-form">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="category-name" required>
                <small id="name-error" class="text-danger" style="display:none;">This category name already exists!</small>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" id="image-input" class="form-control">
            </div>
            
            <!-- Preview Image will be shown here -->
            <div class="form-group">
                <label for="preview">Image Preview</label>
                <div id="image-preview" style="margin-top:10px;">
                    <img id="preview-img" src="#" alt="Your Image Preview" style="display:none; max-width: 200px; max-height: 200px;"/>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary" id="submit-btn">Add Category</button>
        </form>
    </div>
@endsection
@section('css')
<style>
#name-error {
    color: red;
    font-size: 14px;
}
</style>
@stop




@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get references to the input, the preview image, and the submit button
        const imageInput = document.getElementById('image-input');
        const previewImg = document.getElementById('preview-img');
        const categoryNameInput = document.getElementById('category-name');
        const nameError = document.getElementById('name-error');
        const submitBtn = document.getElementById('submit-btn');

        // Add an event listener to handle file selection for image preview
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                };

                reader.readAsDataURL(file);
            } else {
                previewImg.style.display = 'none';
            }
        });

        // Add an event listener to handle the name input for AJAX validation
        categoryNameInput.addEventListener('input', function() {
            const name = categoryNameInput.value;

            if (name.length > 0) {
                // Make an AJAX request to check if the category name exists
                fetch('{{ route("categories.check-name") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name: name })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        // If the name already exists, show an error and disable the submit button
                        nameError.style.display = 'block';
                        submitBtn.disabled = true;
                    } else {
                        // If the name is unique, hide the error and enable the submit button
                        nameError.style.display = 'none';
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                // If the input is empty, hide the error and enable the submit button
                nameError.style.display = 'none';
                submitBtn.disabled = false;
            }
        });
    });
</script>
@stop
