<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductOptionController extends Controller
{

    public function create(Product $product)
    {
        $product->load('images', 'category');
        return Inertia::render('product/option/Create', [
            'product' => $product
        ]);
    }

    public function store(Request $request, Product $product)
    {
        // Validate form input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:product_options,name'],
            'category' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'string'],
            'unit' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'], // max:10MB
        ]);

        $size = $validated['size'] ?
            ($validated['unit'] ?? null ?
                $validated['size'] . ',' . $validated['unit'] :
                $validated['size']) : null;

        // Store product
        $product = ProductOption::create([
            'name' => $validated['name'],
            'product_id' => $product->id,
            'price' => $validated['price'],
            'type' => $validated['type'],
            'stock' => $validated['stock'],
            'size' =>  $size,
            'description' => $validated['description'],
        ]);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image) {
                    $path = $image->store('product_images/variants', 'public');

                    $product->images()->create([
                        'image_path' => $path,
                    ]);
                }
            }
        }

        return redirect()->route('product.show', $product)->with('status', [
            'type' => 'success',
            'message' => 'Product variant named ' .  $validated['name'] . ' created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(?Product $product, ProductOption $option)
    {
        $option->load(['product', 'product.category', 'images', 'product.images']);
        [$option->size, $option->unit] = $this->splitSize($option->size);
        return Inertia::render('product/option/Show', [
            'option' => $option,
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
    public function edit(Product $product, ProductOption $option)
    {
        $option->load(['product.category', 'images', 'product']);
        [$option->size, $option->unit] = $this->splitSize($product->size);

        return Inertia::render('product/option/Edit', [
            'option' => $option,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, ProductOption $option)
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
        $option->update([
            'name' => $validated['name'],
            'product_id' => $product->id,
            'price' => $validated['price'],
            'type' => $validated['type'],
            'stock' => $validated['stock'],
            'size' =>  $size,
            'description' => $validated['description'],
        ]);


        // Handle deleted images (from frontend 'delete' flags)
        foreach (($deleteImages ?? []) as $key => $val) {
            if ($val === 'delete') {
                $oldImage = $option->images->get($key);
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
                    $oldImage = $option->images->get($key) ?? null;
                    Storage::disk('public')->delete($oldImage?->image_path ?? '');
                    $path = $newImage->store('product_images/variants', 'public');
                    if ($path) {
                        $oldImage ? $oldImage->update(['image_path' => $path]) :
                            $option->images()->create([
                                'image_path' => $path,
                            ]);
                    }
                }
            }
        }

        // dd($product->images);
        return redirect()->route('product.option.show', [
            'product' => $product,
            'option' => $option
        ])->with(
            'status',
            [
                'type' => 'success',
                'message' => 'Product variant named ' .  $validated['name'] . ' updated.'
            ]
        );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product,  ProductOption $option)
    {
        // Delete image files from storage
        foreach ($option->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete image records
        $option->images()->delete();

        // Delete the product
        $option->delete();

        return redirect()->route('product.show', $product)->with('status', [
            'type' => 'success',
            'message' => 'Product variant named '. $option->name .'deleted successfully.'
        ]);
    }
}
