<?php

namespace App;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log; // Добавьте этот импорт

class Cart
{
    /**
     * Получить всю корзину
     */
    public static function getCart()
    {
        return session()->get('cart', []);
    }

    /**
     * Сохранить корзину
     */
    public static function saveCart($cart)
    {
        session()->put('cart', $cart);
    }

    /**
     * Добавить товар в корзину
     */
    public static function add($productId, $productData, $quantity = 1)
    {
        $cart = self::getCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $productId,
                'name' => $productData['name'],
                'price' => $productData['price'],
                'image' => $productData['image'] ?? null,
                'quantity' => $quantity
            ];
        }

        self::saveCart($cart);

        return [
            'success' => true,
            'message' => 'Товар "' . $productData['name'] . '" добавлен в корзину'
        ];
    }

    /**
     * Обновить количество товара
     */
    public static function update($productId, $quantity)
    {
        $cart = self::getCart();

        if (!isset($cart[$productId])) {
            return [
                'success' => false,
                'message' => 'Товар не найден в корзине'
            ];
        }

        $productName = $cart[$productId]['name'];

        if ($quantity <= 0) {
            unset($cart[$productId]);
            $message = 'Товар "' . $productName . '" удален из корзины';
        } else {
            $cart[$productId]['quantity'] = $quantity;
            $message = 'Количество товара "' . $productName . '" обновлено';
        }

        self::saveCart($cart);

        return [
            'success' => true,
            'message' => $message
        ];
    }

    /**
     * Рассчитать общую стоимость
     */
    public static function calculateTotal()
    {
        $cart = self::getCart();
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    /**
     * Получить количество товаров в корзине
     */
    public static function getCount()
    {
        $cart = self::getCart();
        $count = 0;

        foreach ($cart as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }

    /**
     * Очистить корзину
     */
    public static function clear()
    {
        // Используем фасад Log вместо \Log
        Log::info('Clearing cart. Current cart:', [session()->get('cart', [])]);
        
        session()->forget('cart');

        $cartAfter = session()->get('cart', []);
        Log::info('Cart after clearing:', [$cartAfter]);
        
        return [
            'success' => true,
            'message' => 'Корзина очищена'
        ];
    }
}
