<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\Order;

class ApiController extends Controller
{
    public function handle(Request $request)
    {
        $action = $request->input('action');
        
        switch ($action) {
            case 'add_to_cart':
                $result = $this->addToCart($request);
                return redirect()->route('home')->with(
                    $result['success'] ? 'success' : 'error',
                    $result['message']
                );
                
            case 'update_cart':
                $result = $this->updateCart($request);
                return redirect()->route('cart.index')->with(
                    $result['success'] ? 'success' : 'error',
                    $result['message']
                );
                
            case 'create_order':
                $result = $this->createOrder($request);
                return redirect()->route('cart.index')->with(
                    $result['success'] ? 'success' : 'error',
                    $result['message']
                );
                
            default:
                return redirect()->back()->with('error', 'Неизвестное действие');
        }
    }
    
    private function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $productData = session('products_data', []);
        
        if (!isset($productData[$productId])) {
            return [
                'success' => false,
                'message' => 'Товар не найден'
            ];
        }
        
        $result = Cart::add($productId, $productData[$productId]);
        return $result;
    }
    
    private function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        
        if (!is_numeric($quantity)) {
            return [
                'success' => false,
                'message' => 'Некорректное количество'
            ];
        }
        
        $result = Cart::update($productId, (int)$quantity);
        return $result;
    }
    
    private function createOrder(Request $request)
{
    // Используем trim для удаления пробелов в начале и конце
    $name = trim($request->input('name'));
    $email = trim($request->input('email'));
    
    // Валидация
    $errors = [];
    
    // Проверка имени
    if (empty($name)) {
        $errors[] = 'Заполните поле "Ваше имя"';
    } elseif (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s\-]+$/u', $name)) {
        $errors[] = 'Имя может содержать только буквы, пробелы и дефисы';
    } elseif (strlen($name) < 2) {
        $errors[] = 'Имя должно содержать минимум 2 символа';
    }
    
    // Проверка email
    if (empty($email)) {
        $errors[] = 'Заполните поле "Email"';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный email адрес';
    }
    
    // Если есть ошибки
    if (!empty($errors)) {
        return [
            'success' => false,
            'message' => implode('<br>', $errors)
        ];
    }
    
    $cart = Cart::getCart();
    
    if (empty($cart)) {
        return [
            'success' => false,
            'message' => 'Корзина пуста'
        ];
    }
    
    // Отправка письма
    $mailResult = Order::sendOrderEmail($name, $email, $cart);
    
    if ($mailResult['success']) {
        // Очищаем корзину после успешного оформления
        Cart::clear();
        return [
            'success' => true,
            'message' => 'Заказ успешно оформлен! Письмо отправлено на ' . $email
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Ошибка при отправке письма: ' . $mailResult['message']
        ];
    }
}
}
