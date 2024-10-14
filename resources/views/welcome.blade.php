<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Web Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .category-box,
        .product-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: transform 0.2s;
        }

        .category-box img,
        .product-box img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .category-box:hover,
        .product-box:hover {
            transform: scale(1.05);
        }

        footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">My Website</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#categories">Categories</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <!-- Home Section -->
        <div id="home">
            <h2 class="mb-4">Home - Categories & Products</h2>
            
            <!-- Categories Section -->
            <div id="categories" class="container mt-5">
                <h2 class="mb-4">Categories</h2>
                <div class="row">
                    <!-- Loop through each category -->
                    @foreach($categories as $category)
                        <div class="col-md-4 mb-4">
                            <div class="category-box">
                                <!-- Display Category Image and Name Dynamically -->
                                <img src="{{ $category->image }}" alt="{{ $category->name }}">
                                <h5>{{ $category->name }}</h5>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Products Section -->
            <div id="products" class="container mt-5">
                <h2 class="mb-4">Products</h2>
                <div class="row">
                    <!-- Loop through each product -->
                    @foreach($products as $product)
                    
                        <div class="col-md-4 mb-4">
                            <div class="product-box">
                                <!-- Display Product Image and Name Dynamically -->
                                <img src="{{ $product->image }}" alt="{{ $product->name }}">
                                <h5>{{ $product->name }}</h5>
                                <p>{{ $product->description }}</p>
                                <p><strong>Price:</strong> ${{ $product->price }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5">
        <p>&copy; 2024 My Website</p>
    </footer>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
