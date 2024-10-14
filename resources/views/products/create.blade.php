@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Add Product</h1>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="category_ids">Categories</label>
            <div class="dropdown">
                <!-- Custom dropdown display -->
                <div id="dropdown-btn" class="dropdown-btn">Select Categories</div>
                <!-- Dropdown options (will be toggled) -->
                <div id="dropdown-options" class="dropdown-options">
                    @foreach($categories as $category)
                        <div class="dropdown-option" data-id="{{ $category->id }}">
                            {{ $category->name }}
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Hidden select element to store selected values -->
            <select id="category-select" name="category_ids[]" class="form-control" multiple hidden>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <!-- Container where selected categories will appear as tags -->
            <div id="selected-categories" class="mt-3"></div>
        </div>

        <div class="form-group">
            <label for="images">Images</label>
            <input type="file" name="image" id="image-input" class="form-control">
            <!-- Image preview -->
            <div id="image-preview" style="margin-top: 10px;">
                <img id="preview-img" src="#" alt="Image Preview"
                    style="display: none; max-width: 200px; max-height: 200px;">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>
@endsection

@section('css')
<style>
    /* Style for dropdown button */
    .dropdown-btn {
        width: 100%;
        padding: 10px;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-radius: 4px;
        cursor: pointer;
        position: relative;
    }

    /* Style for dropdown options */
    .dropdown-options {
        display: none;
        /* Hidden by default */
        background-color: white;
        border: 1px solid #ced4da;
        max-height: 200px;
        overflow-y: auto;
        position: absolute;
        width: 100%;
        z-index: 1000;
        margin-top: 5px;
    }

    /* Individual option styling */
    .dropdown-option {
        padding: 10px;
        cursor: pointer;
    }

    /* Hover effect for options */
    .dropdown-option:hover {
        background-color: #e9ecef;
    }

    /* Style for category tags */
    .category-tag {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        margin: 3px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .category-tag:hover {
        background-color: #0056b3;
    }

    /* Margin between the select box and the tags container */
    #selected-categories {
        margin-top: 10px;
    }
</style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownBtn = document.getElementById('dropdown-btn');
        const dropdownOptions = document.getElementById('dropdown-options');
        const selectElement = document.getElementById('category-select');
        const selectedCategoriesContainer = document.getElementById('selected-categories');
        const imageInput = document.getElementById('image-input');
        const imagePreview = document.getElementById('preview-img');

        // Toggle dropdown visibility on click
        dropdownBtn.addEventListener('click', function () {
            dropdownOptions.style.display = dropdownOptions.style.display === 'block' ? 'none' : 'block';
        });

        // Handle option selection
        const options = dropdownOptions.querySelectorAll('.dropdown-option');
        options.forEach(function (option) {
            option.addEventListener('click', function () {
                const categoryId = option.getAttribute('data-id');
                const categoryName = option.textContent;

                const alreadySelected = Array.from(selectElement.options).find(opt => opt.value === categoryId && opt.selected);

                if (!alreadySelected) {
                    const selectOption = Array.from(selectElement.options).find(opt => opt.value === categoryId);
                    if (selectOption) {
                        selectOption.selected = true;
                    }

                    const tag = document.createElement('div');
                    tag.classList.add('category-tag');
                    tag.textContent = categoryName;

                    tag.addEventListener('click', function () {
                        selectOption.selected = false;
                        tag.remove();
                    });

                    selectedCategoriesContainer.appendChild(tag);
                }

                dropdownOptions.style.display = 'none';
            });
        });

        document.addEventListener('click', function (event) {
            if (!dropdownBtn.contains(event.target) && !dropdownOptions.contains(event.target)) {
                dropdownOptions.style.display = 'none';
            }
        });

        // Image Preview Functionality
        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block'; // Show the image preview
                }

                reader.readAsDataURL(file); // Read the image as a data URL
            } else {
                imagePreview.style.display = 'none'; // Hide the image preview if no file is selected
            }
        });
    });

</script>
@stop