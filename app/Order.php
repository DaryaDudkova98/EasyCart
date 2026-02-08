<?php

namespace App;

class Order
{
    /**
     * Create a new class instance.
     */
    public static function sendOrderEmail($name, $email, $cart)
    {
        $total = 0;
        $orderItems = '';
        
        // Формируем список товаров
        foreach ($cart as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $total += $itemTotal;
            
            $orderItems .= "• " . $item['name'] . " - " . $item['quantity'] . " шт. × " . 
                          number_format($item['price'], 2, ',', ' ') . " BYN = " . 
                          number_format($itemTotal, 2, ',', ' ') . " BYN\n";
        }
        
        // Формируем текст письма
        $subject = "Заказ с сайта EasyCart";
        $message = "Здравствуйте, " . $name . "!\n\n";
        $message .= "Благодарим за ваш заказ в интернет-магазине EasyCart.\n\n";
        $message .= "Состав заказа:\n";
        $message .= $orderItems . "\n";
        $message .= "Итого: " . number_format($total, 2, ',', ' ') . " BYN\n\n";
        $message .= "Ваши контактные данные:\n";
        $message .= "Имя: " . $name . "\n";
        $message .= "Email: " . $email . "\n\n";
        $message .= "С уважением,\n";
        $message .= "Интернет-магазин EasyCart\n";
        $message .= "Телефон: +375 (XX) XXX-XX-XX\n";
        $message .= "Email: info@easycart.by";
        
        // Заголовки письма
        $headers = "From: info@easycart.by\r\n";
        $headers .= "Reply-To: info@easycart.by\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        $headers .= "Content-type: text/plain; charset=utf-8\r\n";
        
        // Отправляем письмо
        try {
            $mailSent = mail($email, $subject, $message, $headers);
            
            if ($mailSent) {
                return [
                    'success' => true,
                    'message' => 'Письмо отправлено успешно'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Функция mail() вернула false'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Ошибка при отправке: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Создать заказ
     */
    public static function create($customerData)
    {
        $cart = Cart::getCart();
        
        if (empty($cart)) {
            return [
                'success' => false,
                'message' => 'Корзина пуста'
            ];
        }
        
        // Валидация данных клиента
        if (empty($customerData['name']) || empty($customerData['email'])) {
            return [
                'success' => false,
                'message' => 'Заполните все обязательные поля'
            ];
        }
        
        // Отправляем письмо
        $mailResult = self::sendOrderEmail(
            $customerData['name'],
            $customerData['email'],
            $cart
        );
        
        if ($mailResult['success']) {
            // Очищаем корзину после успешного оформления
            Cart::clear();
            
            return [
                'success' => true,
                'message' => 'Заказ успешно оформлен! Письмо отправлено на ' . $customerData['email'],
                'cart' => $cart,
                'total' => Cart::calculateTotal($cart),
                'customer' => $customerData
            ];
        }
        
        return $mailResult;
    }
}
