<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = DB::table('categories')->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $subcategories = DB::table('subcategories')->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $products = DB::table('products')
            ->leftJoin('product_images', function ($join) {
                $join->on('product_images.product_id', '=', 'products.id')->where('product_images.is_primary', true);
            })
            ->when(!request()->boolean('manage'), fn ($query) => $query->where('products.status', 'active'))
            ->whereNull('products.deleted_at')
            ->select('products.*', 'product_images.path as image_path')->get();

        $products->each(function ($product) {
            $category = DB::table('categories')->find($product->category_id);
            $subcategory = DB::table('subcategories')->find($product->subcategory_id);
            $product->cat = $category?->slug;
            $product->sub = $subcategory?->name;
            $product->category = $category?->name;
            $product->sizes = DB::table('product_variants')->where('product_id', $product->id)->where('is_active', true)->pluck('size')->values();
            $product->variants = DB::table('product_variants')->where('product_id', $product->id)->where('is_active', true)->get(['id', 'size', 'stock_quantity']);
            $product->stock = (int) DB::table('product_variants')->where('product_id', $product->id)->sum('stock_quantity');
            $product->image = $product->image_path ? Storage::disk('public')->url($product->image_path) : null;
            unset($product->image_path);
        });

        return response()->json(compact('categories', 'subcategories', 'products'));
    }

    public function manage(): JsonResponse
    {
        request()->merge(['manage' => true]);
        return $this->index();
    }

    public function deleteCategory(int $category): JsonResponse
    {
        abort_if(DB::table('products')->where('category_id', $category)->exists(), 422, 'Move or delete products in this category first.');
        DB::table('categories')->where('id', $category)->delete();
        return response()->json(['ok' => true]);
    }

    public function deleteSubcategory(int $subcategory): JsonResponse
    {
        abort_if(DB::table('products')->where('subcategory_id', $subcategory)->exists(), 422, 'Move or delete products in this subcategory first.');
        $deleted = DB::table('subcategories')->where('id', $subcategory)->delete();
        abort_if(!$deleted, 404);
        return response()->json(['ok' => true]);
    }

    public function storeCategory(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subcategories' => ['required', 'array', 'min:1'],
            'subcategories.*' => ['required', 'string', 'max:255'],
        ]);
        $slug = Str::slug($data['name']);
        if (DB::table('categories')->where('slug', $slug)->exists()) {
            return response()->json(['message' => 'This category already exists.'], 422);
        }
        $categoryId = DB::transaction(function () use ($data, $slug) {
            $id = DB::table('categories')->insertGetId(['name' => $data['name'], 'slug' => $slug, 'created_at' => now(), 'updated_at' => now()]);
            foreach (array_unique($data['subcategories']) as $name) {
                DB::table('subcategories')->insert(['category_id' => $id, 'name' => $name, 'slug' => Str::slug($name), 'created_at' => now(), 'updated_at' => now()]);
            }
            return $id;
        });
        return response()->json(['id' => $categoryId], 201);
    }

    public function storeSubcategory(Request $request): JsonResponse
    {
        $data = $request->validate(['category_id' => ['required', 'integer', 'exists:categories,id'], 'name' => ['required', 'string', 'max:255']]);
        $slug = Str::slug($data['name']);
        if (DB::table('subcategories')->where('category_id', $data['category_id'])->where('slug', $slug)->exists()) {
            return response()->json(['message' => 'This subcategory already exists.'], 422);
        }
        $id = DB::table('subcategories')->insertGetId(['category_id' => $data['category_id'], 'name' => $data['name'], 'slug' => $slug, 'created_at' => now(), 'updated_at' => now()]);
        return response()->json(['id' => $id], 201);
    }

    public function storeProduct(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'], 'category_id' => ['required', 'integer', 'exists:categories,id'],
            'subcategory_id' => ['required', 'integer', 'exists:subcategories,id'], 'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'], 'status' => ['required', 'in:active,draft,archived'],
            'sizes' => ['required', 'array', 'min:1'], 'sizes.*.name' => ['required', 'string', 'max:50'],
            'sizes.*.stock' => ['required', 'integer', 'min:0'], 'image' => ['nullable', 'image', 'max:5120'], 'image_data' => ['nullable', 'string'],
        ]);
        if (!$request->hasFile('image') && blank($data['image_data'] ?? null)) {
            return response()->json(['message' => 'A product image is required.'], 422);
        }
        $productId = DB::transaction(function () use ($data, $request) {
            $productId = DB::table('products')->insertGetId([
                'category_id' => $data['category_id'], 'subcategory_id' => $data['subcategory_id'], 'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . Str::lower(Str::random(6)), 'description' => $data['description'] ?? null,
                'price' => $data['price'], 'status' => $data['status'], 'created_at' => now(), 'updated_at' => now(),
            ]);
            foreach ($data['sizes'] as $variant) {
                DB::table('product_variants')->insert(['product_id' => $productId, 'sku' => Str::upper(Str::random(10)), 'size' => $variant['name'], 'stock_quantity' => $variant['stock'], 'created_at' => now(), 'updated_at' => now()]);
            }
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
            } else {
                [$header, $encoded] = array_pad(explode(',', $data['image_data'], 2), 2, null);
                $extension = str_contains($header ?? '', 'png') ? 'png' : (str_contains($header ?? '', 'webp') ? 'webp' : 'jpg');
                $path = 'products/' . Str::random(20) . '.' . $extension;
                Storage::disk('public')->put($path, base64_decode($encoded));
            }
            DB::table('product_images')->insert(['product_id' => $productId, 'path' => $path, 'is_primary' => true, 'created_at' => now(), 'updated_at' => now()]);
            return $productId;
        });
        return response()->json(['id' => $productId], 201);
    }

    public function updateProduct(Request $request, int $product): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'subcategory_id' => ['required', 'integer', 'exists:subcategories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,draft,archived'],
            'sizes' => ['required', 'array', 'min:1'],
            'sizes.*.name' => ['required', 'string', 'max:50'],
            'sizes.*.stock' => ['required', 'integer', 'min:0'],
            'image_data' => ['nullable', 'string'],
        ]);
        $record = DB::table('products')->where('id', $product)->whereNull('deleted_at')->first();
        abort_unless($record, 404);
        DB::transaction(function () use ($data, $request, $product) {
            DB::table('products')->where('id', $product)->update([
                'category_id' => $data['category_id'], 'subcategory_id' => $data['subcategory_id'], 'name' => $data['name'],
                'description' => $data['description'] ?? null, 'price' => $data['price'], 'status' => $data['status'], 'updated_at' => now(),
            ]);
            DB::table('product_variants')->where('product_id', $product)->delete();
            foreach ($data['sizes'] as $variant) {
                DB::table('product_variants')->insert(['product_id' => $product, 'sku' => Str::upper(Str::random(10)), 'size' => $variant['name'], 'stock_quantity' => $variant['stock'], 'created_at' => now(), 'updated_at' => now()]);
            }
            if (!empty($data['image_data'])) {
                [$header, $encoded] = array_pad(explode(',', $data['image_data'], 2), 2, null);
                $extension = str_contains($header ?? '', 'png') ? 'png' : (str_contains($header ?? '', 'webp') ? 'webp' : 'jpg');
                $path = 'products/' . Str::random(20) . '.' . $extension;
                Storage::disk('public')->put($path, base64_decode($encoded));
                DB::table('product_images')->where('product_id', $product)->update(['is_primary' => false]);
                DB::table('product_images')->insert(['product_id' => $product, 'path' => $path, 'is_primary' => true, 'created_at' => now(), 'updated_at' => now()]);
            }
        });
        return response()->json(['ok' => true]);
    }

    public function updateStock(Request $request, int $variant): JsonResponse
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:0'], 'note' => ['nullable', 'string', 'max:500']]);
        $updated = DB::transaction(function () use ($data, $variant) {
            $item = DB::table('product_variants')->where('id', $variant)->lockForUpdate()->first();
            abort_unless($item, 404);
            $change = $data['quantity'] - $item->stock_quantity;
            DB::table('product_variants')->where('id', $variant)->update(['stock_quantity' => $data['quantity'], 'updated_at' => now()]);
            if ($change !== 0) DB::table('inventory_movements')->insert(['product_variant_id' => $variant, 'quantity_change' => $change, 'quantity_after' => $data['quantity'], 'reason' => 'adjustment', 'note' => $data['note'] ?? null, 'created_at' => now(), 'updated_at' => now()]);
            return $data['quantity'];
        });
        return response()->json(['quantity' => $updated]);
    }

    public function adjustProductStock(Request $request, int $product): JsonResponse
    {
        $data = $request->validate(['change' => ['required', 'integer', 'between:-100000,100000']]);
        $variant = DB::table('product_variants')->where('product_id', $product)->where('is_active', true)->orderBy('id')->first();
        abort_unless($variant, 404, 'This product has no active inventory variant.');
        $quantity = max(0, $variant->stock_quantity + $data['change']);
        return $this->updateStock(Request::create('/api/inventory/' . $variant->id, 'PATCH', ['quantity' => $quantity]), $variant->id);
    }
}
