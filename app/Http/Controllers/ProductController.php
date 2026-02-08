<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = [
            ['id' => 1, 'name' => 'Тетрадь в клетку', 'price' => 1.80, 'image' => 'notebook-checkered.png'],
            ['id' => 2, 'name' => 'Тетрадь в линейку', 'price' => 1.95, 'image' => 'notebook-lined.png'],
            ['id' => 3, 'name' => 'Блокнот ежедневник', 'price' => 18.50, 'image' => 'notebook-daily.png'],
            ['id' => 4, 'name' => 'Скетчбук', 'price' => 22.15, 'image' => 'sketchbook.png'],
            ['id' => 5, 'name' => 'Ручка гелевая синяя', 'price' => 2.20, 'image' => 'pen-gel.png'],
            ['id' => 6, 'name' => 'Набор карандашей', 'price' => 8.35, 'image' => 'pencil-set.png'],
            ['id' => 7, 'name' => 'Блокнот в линейку', 'price' => 3.00, 'image' => 'notebook-small.png'],
            ['id' => 8, 'name' => 'Простой карандаш', 'price' => 0.35, 'image' => 'pencil-simple.png'],
        ];
        
        $productData = [];
        foreach ($products as $product) {
            $imageName = $product['image'];
            $fullPath = public_path('images/products/' . $imageName);
            $imageExists = file_exists($fullPath);
            
            $imagePath = 'images/products/' . $imageName;

            $productData[$product['id']] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $imageName,
                'image_path' => $imagePath,
                'has_image' => $imageExists
            ];
        }
        session(['products_data' => $productData]);

        return view('welcome', [
            'products' => $products,
            'productData' => $productData
        ]);
    }
}