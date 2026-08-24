<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Men' => ['Shirts', 'T-Shirts', 'Trousers', 'Jackets', 'Ethnic Wear'],
            'Women' => ['Dresses', 'Tops & Shirts', 'Trousers', 'Knitwear', 'Ethnic Wear'],
            'Kids' => ['T-Shirts', 'Frocks', 'Jackets', 'Bottoms'],
        ];

        foreach ($categories as $categoryName => $subcategories) {
            $categoryId = DB::table('categories')->where('slug', Str::slug($categoryName))->value('id');
            if (!$categoryId) $categoryId = DB::table('categories')->insertGetId([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            foreach ($subcategories as $subcategoryName) {
                DB::table('subcategories')->updateOrInsert([
                    'category_id' => $categoryId,
                    'slug' => Str::slug($subcategoryName),
                ], [
                    'name' => $subcategoryName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $products = [
            ['name' => 'RASHWEAR Classic Tee', 'category' => 'Men', 'subcategory' => 'T-Shirts', 'price' => 1299, 'description' => 'Soft cotton tee with a classic fit.', 'sizes' => ['S' => 10, 'M' => 12, 'L' => 8]],
            ['name' => 'Heritage Kurta Set', 'category' => 'Women', 'subcategory' => 'Ethnic Wear', 'price' => 2499, 'description' => 'Festival-ready kurta set in rich colours.', 'sizes' => ['S' => 5, 'M' => 8, 'L' => 5]],
            ['name' => 'Kids Adventure Jacket', 'category' => 'Kids', 'subcategory' => 'Jackets', 'price' => 2150, 'description' => 'Warm, durable jacket for everyday play.', 'sizes' => ['4-5Y' => 3, '6-7Y' => 3]],
            ['name' => 'Everyday Oxford Shirt', 'category' => 'Men', 'subcategory' => 'Shirts', 'price' => 2450, 'description' => 'Breathable cotton oxford shirt.', 'sizes' => ['S' => 8, 'M' => 10, 'L' => 8, 'XL' => 4]],
            ['name' => 'Brushed Flannel Shirt', 'category' => 'Men', 'subcategory' => 'Shirts', 'price' => 2650, 'description' => 'Soft brushed cotton flannel shirt.', 'sizes' => ['S' => 6, 'M' => 8, 'L' => 8, 'XL' => 4]],
            ['name' => 'Heavy Cotton Crewneck Tee', 'category' => 'Men', 'subcategory' => 'T-Shirts', 'price' => 1350, 'description' => 'Heavyweight combed cotton tee.', 'sizes' => ['S' => 10, 'M' => 12, 'L' => 10, 'XL' => 6]],
            ['name' => 'Striped Pocket Tee', 'category' => 'Men', 'subcategory' => 'T-Shirts', 'price' => 1250, 'description' => 'Relaxed striped cotton tee.', 'sizes' => ['S' => 8, 'M' => 10, 'L' => 8, 'XL' => 4]],
            ['name' => 'Tapered Chino Trouser', 'category' => 'Men', 'subcategory' => 'Trousers', 'price' => 2200, 'description' => 'Tapered brushed cotton chino.', 'sizes' => ['30' => 5, '32' => 8, '34' => 8, '36' => 4]],
            ['name' => 'Relaxed Denim Jean', 'category' => 'Men', 'subcategory' => 'Trousers', 'price' => 2900, 'description' => 'Relaxed straight mid-wash jean.', 'sizes' => ['30' => 5, '32' => 8, '34' => 8, '36' => 4]],
            ['name' => 'Wool-Blend Overcoat', 'category' => 'Men', 'subcategory' => 'Jackets', 'price' => 6900, 'description' => 'Warm structured wool-blend coat.', 'sizes' => ['M' => 3, 'L' => 4, 'XL' => 2]],
            ['name' => 'Quilted Bomber Jacket', 'category' => 'Men', 'subcategory' => 'Jackets', 'price' => 4200, 'description' => 'Lightly quilted everyday bomber.', 'sizes' => ['M' => 4, 'L' => 6, 'XL' => 3]],
            ['name' => 'Daura-Collar Kurta', 'category' => 'Men', 'subcategory' => 'Ethnic Wear', 'price' => 2800, 'description' => 'Modern handloom daura collar kurta.', 'sizes' => ['S' => 4, 'M' => 6, 'L' => 6, 'XL' => 3]],
            ['name' => 'Festival Waistcoat Set', 'category' => 'Men', 'subcategory' => 'Ethnic Wear', 'price' => 3600, 'description' => 'Kurta and waistcoat set.', 'sizes' => ['S' => 3, 'M' => 5, 'L' => 5, 'XL' => 2]],
            ['name' => 'Linen Wrap Dress', 'category' => 'Women', 'subcategory' => 'Dresses', 'price' => 3400, 'description' => 'Easy washed linen wrap dress.', 'sizes' => ['XS' => 4, 'S' => 6, 'M' => 6, 'L' => 3]],
            ['name' => 'Tiered Midi Dress', 'category' => 'Women', 'subcategory' => 'Dresses', 'price' => 3100, 'description' => 'Soft tiered cotton midi dress.', 'sizes' => ['XS' => 4, 'S' => 6, 'M' => 6, 'L' => 3]],
            ['name' => 'Relaxed Poplin Shirt', 'category' => 'Women', 'subcategory' => 'Tops & Shirts', 'price' => 2100, 'description' => 'Oversized relaxed poplin shirt.', 'sizes' => ['XS' => 4, 'S' => 6, 'M' => 6, 'L' => 3, 'XL' => 2]],
            ['name' => 'Boxy Cropped Blouse', 'category' => 'Women', 'subcategory' => 'Tops & Shirts', 'price' => 1850, 'description' => 'Soft crepe cropped blouse.', 'sizes' => ['XS' => 4, 'S' => 6, 'M' => 6, 'L' => 3]],
            ['name' => 'High-Waist Wide Trouser', 'category' => 'Women', 'subcategory' => 'Trousers', 'price' => 2600, 'description' => 'Fluid wide-leg trouser.', 'sizes' => ['XS' => 4, 'S' => 6, 'M' => 6, 'L' => 3]],
            ['name' => 'Tapered Ankle Trouser', 'category' => 'Women', 'subcategory' => 'Trousers', 'price' => 2400, 'description' => 'Stretch twill ankle trouser.', 'sizes' => ['XS' => 4, 'S' => 6, 'M' => 6, 'L' => 3]],
            ['name' => 'Soft Cardigan Knit', 'category' => 'Women', 'subcategory' => 'Knitwear', 'price' => 2950, 'description' => 'Lightweight rib-knit cardigan.', 'sizes' => ['S' => 5, 'M' => 7, 'L' => 4]],
            ['name' => 'Turtleneck Sweater', 'category' => 'Women', 'subcategory' => 'Knitwear', 'price' => 2700, 'description' => 'Fine-knit cotton-wool sweater.', 'sizes' => ['XS' => 4, 'S' => 6, 'M' => 6, 'L' => 3]],
            ['name' => 'Handloom Kurta Set', 'category' => 'Women', 'subcategory' => 'Ethnic Wear', 'price' => 3800, 'description' => 'Two-piece handloom cotton set.', 'sizes' => ['S' => 4, 'M' => 6, 'L' => 6, 'XL' => 3]],
            ['name' => 'Embroidered Festival Kurta', 'category' => 'Women', 'subcategory' => 'Ethnic Wear', 'price' => 4200, 'description' => 'Embroidered kurta with dupatta.', 'sizes' => ['S' => 3, 'M' => 5, 'L' => 5, 'XL' => 2]],
            ['name' => 'Little Explorer Tee', 'category' => 'Kids', 'subcategory' => 'T-Shirts', 'price' => 850, 'description' => 'Soft pre-shrunk cotton kids tee.', 'sizes' => ['2-3Y' => 5, '4-5Y' => 7, '6-7Y' => 6, '8-9Y' => 4]],
            ['name' => 'Striped Crew Tee', 'category' => 'Kids', 'subcategory' => 'T-Shirts', 'price' => 790, 'description' => 'Breathable striped crewneck tee.', 'sizes' => ['2-3Y' => 5, '4-5Y' => 7, '6-7Y' => 6, '8-9Y' => 4]],
            ['name' => 'Cotton Pinafore Frock', 'category' => 'Kids', 'subcategory' => 'Frocks', 'price' => 1450, 'description' => 'Adjustable brushed cotton frock.', 'sizes' => ['2-3Y' => 4, '4-5Y' => 6, '6-7Y' => 5]],
            ['name' => 'Floral Party Frock', 'category' => 'Kids', 'subcategory' => 'Frocks', 'price' => 1650, 'description' => 'Soft floral cotton party frock.', 'sizes' => ['2-3Y' => 4, '4-5Y' => 6, '6-7Y' => 5]],
            ['name' => 'Fleece-Lined Jacket', 'category' => 'Kids', 'subcategory' => 'Jackets', 'price' => 1950, 'description' => 'Warm fleece-lined school jacket.', 'sizes' => ['3-4Y' => 4, '5-6Y' => 6, '7-8Y' => 4]],
            ['name' => 'Puffer Jacket', 'category' => 'Kids', 'subcategory' => 'Jackets', 'price' => 2350, 'description' => 'Warm lightweight winter puffer.', 'sizes' => ['3-4Y' => 4, '5-6Y' => 6, '7-8Y' => 4]],
            ['name' => 'Everyday Joggers', 'category' => 'Kids', 'subcategory' => 'Bottoms', 'price' => 990, 'description' => 'Soft brushed cotton joggers.', 'sizes' => ['2-3Y' => 5, '4-5Y' => 7, '6-7Y' => 6, '8-9Y' => 4]],
            ['name' => 'Denim Dungarees', 'category' => 'Kids', 'subcategory' => 'Bottoms', 'price' => 1550, 'description' => 'Adjustable denim dungarees.', 'sizes' => ['2-3Y' => 4, '4-5Y' => 6, '6-7Y' => 5]],
        ];
        foreach ($products as $product) {
            $categoryId = DB::table('categories')->where('name', $product['category'])->value('id');
            $subcategoryId = DB::table('subcategories')->where('category_id', $categoryId)->where('name', $product['subcategory'])->value('id');
            $productId = DB::table('products')->where('slug', Str::slug($product['name']))->value('id');
            if ($productId) continue;
            $productId = DB::table('products')->insertGetId([
                'category_id' => $categoryId, 'subcategory_id' => $subcategoryId, 'name' => $product['name'],
                'slug' => Str::slug($product['name']), 'description' => $product['description'], 'price' => $product['price'],
                'status' => 'active', 'created_at' => now(), 'updated_at' => now(),
            ]);
            foreach ($product['sizes'] as $size => $stock) {
                DB::table('product_variants')->insert([
                    'product_id' => $productId, 'sku' => Str::upper(Str::random(10)), 'size' => $size,
                    'stock_quantity' => $stock, 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }
    }
}
