<?php


namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
          
            $uploadPath = public_path('upload');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Get the uploaded image file
            $image = $request->file('image');

            // Generate a unique filename using the current timestamp
            $filename = now()->format('YmdHis') . '.' . $image->getClientOriginalExtension();

            // Define the image path in the 'public/upload' folder
            $imagePath = "upload/{$filename}";

            // Create image manager with the desired driver
            $manager = new ImageManager(new Driver());

            // Read the uploaded image using the `read()` method
            $img = $manager->read($image->getRealPath());

            // Scale the image to 300px width proportionally
            $img->scale(width: 300);

            // Insert a watermark (if needed, provide the watermark path)
            $watermarkPath = public_path('images/watermark.png');
            if (file_exists($watermarkPath)) {
                $img->place($watermarkPath);
            }

            // Save the modified image as PNG to the 'public/upload' folder
            $img->toPng()->save(public_path($imagePath));

            // Store the image path in the database
            $data['image'] = $imagePath;
        }

        Category::create($data);
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Create the upload directory if it doesn't exist
            $uploadPath = public_path('upload');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Get the uploaded image file
            $image = $request->file('image');

            // Generate a unique filename using the current timestamp
            $filename = now()->format('YmdHis') . '.' . $image->getClientOriginalExtension();

            // Define the image path in the 'public/upload' folder
            $imagePath = "upload/{$filename}";

            // Create image manager with the desired driver
            $manager = new ImageManager(new Driver());

            // Read the uploaded image using the `read()` method
            $img = $manager->read($image->getRealPath());

            // Scale the image to 300px width proportionally
            $img->scale(width: 300);

            // Insert a watermark (if needed, provide the watermark path)
            $watermarkPath = public_path('images/watermark.png');
            if (file_exists($watermarkPath)) {
                $img->place($watermarkPath);
            }

            // Save the modified image as PNG to the 'public/upload' folder
            $img->toPng()->save(public_path($imagePath));

            // Store the image path in the database
            $data['image'] = $imagePath;
        }

        $category->update($data);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }



    public function getDataTable()
    {
        $categories = Category::query(); 

        return DataTables::of($categories)
            ->addColumn('action', function ($row) {
                $editUrl = route('categories.edit', $row->id);
                $deleteUrl = route('categories.destroy', $row->id);
                $csrfToken = csrf_token();
                $deleteForm = <<<HTML
                    <form action="$deleteUrl" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="$csrfToken">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                HTML;

                return '<a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a> ' . $deleteForm;
            })
            ->rawColumns(['action']) 
            ->make(true);
    }




    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function checkCategoryName(Request $request)
    {
        $exists = Category::where('name', $request->name)->exists();

        return response()->json(['exists' => $exists]);
    }



    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}