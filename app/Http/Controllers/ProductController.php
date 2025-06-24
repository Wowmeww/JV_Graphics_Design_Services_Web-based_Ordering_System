<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with(['images', 'category'])
            ->filter(request(['search', 'category', 'stock', 'sort']))
            ->paginate(8)
            ->withQueryString();
        return Inertia::render('product/Index', [
            'products' => $products,
            'categories' => Category::all(['id', 'name']),
            'status' => session('status')
        ]);
    }

    public function create()
    {

        return Inertia::render('product/Create', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate form input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'category' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'string'],
            'unit' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'], // max:10MB
        ]);

        // Find or create the category
        $category = Category::firstOrCreate([
            'name' => $validated['category']
        ]);

        $size = $validated['size'] ?
            ($validated['unit'] ?? null ?
                $validated['size'] . ',' . $validated['unit'] :
                $validated['size']) : null;

        // Store product
        $product = Product::create([
            'name' => $validated['name'],
            'category_id' => $category->id,
            'price' => $validated['price'],
            'type' => $validated['type'],
            'stock' => $validated['stock'],
            'size' => $size,
            'description' => $validated['description'],
        ]);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image) {
                    $path = $image->store('product_images', 'public');

                    $product->images()->create([
                        'image_path' => $path,
                    ]);
                }
            }
        }

        return redirect()->route('product.index')->with('status', [
            'type' => 'success',
            'message' => 'Product created successfully.',
        ]);
    }



    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'options', 'images', 'options.images', 'options.product.category']);
        [$product->size, $product->unit] = $this->splitSize($product->size);
        return Inertia::render('product/Show', [
            'product' => $product,
        ]);
    }

    private function splitSize(?string $size): array
    {
        if ($size && str_contains($size, ',')) {
            return explode(',', $size, 2);
        }

        return [$size, null];
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load(['category', 'options', 'images', 'options.images']);
        [$product->size, $product->unit] = $this->splitSize($product->size);

        return Inertia::render('product/Edit', [
            'product' => $product,
            'categories' => Category::pluck('name')->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // sleep(1);
        //   dd($request->all());
        $deleteImages = $request->images;

        $request['images'] = array_map(function ($image) {
            return $image === 'delete' ? null : $image;
        }, $request->images);


        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'string'],
            'unit' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
        ]);

        $size = $validated['size'] ?
            ($validated['unit'] ?? null ?
                $validated['size'] . ',' . $validated['unit'] :
                $validated['size']) : null;
        // Update product
        $product->update([
            'name' => $validated['name'],
            'category_id' => Category::where('name', $validated['category'])->value('id'),
            'price' => $validated['price'],
            'type' => $validated['type'],
            'stock' => $validated['stock'],
            'size' => $size,
            'description' => $validated['description'],
        ]);


        // Handle deleted images (from frontend 'delete' flags)
        foreach (($deleteImages ?? []) as $key => $val) {
            if ($val === 'delete') {
                $oldImage = $product->images->get($key);
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage->image_path);
                    $oldImage->delete(); // Remove DB record too
                }
            }
        }

        // If images are provided, optionally replace them
        $newImages = $request->file('images');
        if ($newImages) {
            foreach ($newImages as $key => $newImage) {
                if ($newImage) {
                    $oldImage = $product->images->get($key) ?? null;
                    Storage::disk('public')->delete($oldImage?->image_path ?? '');
                    $path = $newImage->store('product_images', 'public');
                    if ($path) {
                        $oldImage ? $oldImage->update(['image_path' => $path]) :
                            $product->images()->create([
                                'image_path' => $path,
                            ]);
                    }
                }
            }
        }

        // dd($product->images);
        return redirect()->route('product.show', $product)->with(
            'status',
            [
                'type' => 'success',
                'message' => 'Product updated successfully.'
            ]
        );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete image files from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete image records
        $product->images()->delete();

        // Delete the product
        $product->delete();

        return redirect()->route('product.index')->with('status', [
            'type' => 'success',
            'message' => 'Product deleted successfully.'
        ]);
    }
}
