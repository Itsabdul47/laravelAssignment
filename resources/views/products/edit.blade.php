@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Add Product</h1>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" id="product-name" required>
            <small id="name-error" class="text-danger" style="display:none;">This product name already exists!</small>
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
            <input type="file" name="image" />
        </div>
        <button type="submit" class="btn btn-primary" id="submit-btn">Add Product</button>
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

    .text-danger {
        color: red;
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
        const productNameInput = document.getElementById('product-name');
        const nameError = document.getElementById('name-error');
        const submitBtn = document.getElementById('submit-btn');

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

                // Check if the category is already selected
                const alreadySelected = Array.from(selectElement.options).find(opt => opt.value === categoryId && opt.selected);

                if (!alreadySelected) {
                    // Mark the corresponding option in the hidden select as selected
                    const selectOption = Array.from(selectElement.options).find(opt => opt.value === categoryId);
                    if (selectOption) {
                        selectOption.selected = true;
                    }

                    // Create a tag for the selected category
                    const tag = document.createElement('div');
                    tag.classList.add('category-tag');
                    tag.textContent = categoryName;

                    // Add a click event to remove the tag and deselect the option
                    tag.addEventListener('click', function () {
                        selectOption.selected = false; // Deselect the option
                        tag.remove(); // Remove the tag
                    });

                    // Append the tag to the selected categories container
                    selectedCategoriesContainer.appendChild(tag);
                }

                // Hide the dropdown after selecting
                dropdownOptions.style.display = 'none';
            });
        });

        // Close the dropdown if clicked outside of it
        document.addEventListener('click', function (event) {
            if (!dropdownBtn.contains(event.target) && !dropdownOptions.contains(event.target)) {
                dropdownOptions.style.display = 'none';
            }
        });

        // AJAX request to check for product name duplication
        productNameInput.addEventListener('input', function () {
            const name = productNameInput.value;

            if (name.length > 0) {
                // Make an AJAX request to check if the product name exists
                fetch('{{ route("products.check-name") }}', {
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
