<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function show($id)
    {
        $product = Product::with('categories', 'images')->findOrFail($id);
        return view('products.show', compact('product'));
    }
    public function datatable()
    {
        // Fetch products along with categories and images
        $products = Product::with('categories', 'images')->select(['id', 'name', 'description', 'price']);

        return DataTables::of($products)
            ->addColumn('categories', function ($product) {
                // Return a string of comma-separated category names
                return $product->categories->pluck('name')->join(', ');
            })
            ->addColumn('images', function ($product) {
                // Generate HTML for each image and display them
                return $product->images->map(function ($image) {
                    // Use the asset() helper to ensure correct image path
                    return '<img src="' . asset($image) . '" width="50" height="50" style="object-fit:cover;"/>';
                })->implode(' ');
            })
            ->addColumn('action', function ($product) {
                // Provide action buttons for editing and deleting
                return '
            <a href="' . route('products.edit', $product->id) . '" class="btn btn-sm btn-primary">Edit</a>
            <a href="' . route('products.destroy', $product->id) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>';
            })
            ->rawColumns(['images', 'action']) // Ensure the images and action columns are treated as raw HTML
            ->make(true);
    }


    public function getdatatable()
    {
        $products = Product::with('categories')->select(['id', 'name', 'description', 'price', 'image']);


        return DataTables::of($products)
            ->addColumn('categories', function ($product) {
                return $product->categories->pluck('name')->join(', ');
            })
            ->addColumn('images', function ($product) {
                $images = $product->images;

                $imageHtml = '';
                if (is_array($images)) {
                    foreach ($images as $image) {

                        $imageHtml .= '<button class="btn btn-sm btn-primary" onclick="showImageModal(\'' . asset($image) . '\')">👁️ Preview</button> ';
                    }
                }
                return $imageHtml;
            })
            ->addColumn('action', function ($product) {
                return '
                <a href="' . route('products.edit', $product->id) . '" class="btn btn-sm btn-primary">Edit</a>
                <a href="' . route('products.destroy', $product->id) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>';
            })
            ->rawColumns(['images', 'action']) 
            ->make(true);
    }



    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $data = $request->except(['category_ids', 'image']);

        if ($request->hasFile('image')) {
    
            $uploadPath = public_path('upload/products');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $filename = now()->format('YmdHis') . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = "upload/products/{$filename}";

            $manager = new ImageManager(new Driver());

            $img = $manager->read($request->file('image')->getRealPath());

            $img->scale(width: 300);

            $watermarkPath = public_path('images/watermark.png');
            if (file_exists($watermarkPath)) {
                $img->place($watermarkPath);
            }

            $img->save(public_path($imagePath));

            $data['image'] = $imagePath;
        }
        $product = Product::create($data);

        $product->categories()->sync(array_filter($request->category_ids));

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }



    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation for a single image
        ]);

        $product = Product::findOrFail($id);

        // Create an array of all request data except the image and category_ids
        $data = $request->except(['category_ids', 'image']);

        // Process the image file if uploaded
        if ($request->hasFile('image')) {
            // Optionally, delete the old image from storage
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            // Create the upload directory if it doesn't exist
            $uploadPath = public_path('upload/products');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Generate a unique filename
            $filename = now()->format('YmdHis') . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = "upload/products/{$filename}";

            // Use ImageManager with the GD Driver
            $manager = new ImageManager(new Driver());

            // Read and process the image
            $img = $manager->read($request->file('image')->getRealPath());

            // Resize the image to 300px width while keeping the aspect ratio
            $img->scale(width: 300);

            // Optional watermark
            $watermarkPath = public_path('images/watermark.png');
            if (file_exists($watermarkPath)) {
                $img->place($watermarkPath);
            }

            // Save the image to the defined path
            $img->save(public_path($imagePath));

            // Add the image path to the data array to be updated in the database
            $data['image'] = $imagePath;
        }

        // Update product with the correct data (excluding category_ids)
        $product->update($data);

        // Sync categories with the product
        $product->categories()->sync(array_filter($request->category_ids));

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }



    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Optionally, remove associated images from storage
        if ($product->images) {
            foreach ($product->images as $imagePath) {
                if (File::exists(public_path($imagePath))) {
                    File::delete(public_path($imagePath));
                }
            }
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
    public function checkProductName(Request $request)
    {
        // Check if a product with the given name already exists
        $exists = Product::where('name', $request->name)->exists();

        // Return a JSON response indicating whether the name exists
        return response()->json(['exists' => $exists]);
    }

}
