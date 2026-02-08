<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Корзина - EasyCart' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1>Корзина</h1>
                        <h4>Ваши товары</h4>

                        <!-- Товары в корзине -->
                        <div class="cart-items mb-4">
                            @if (count($cartItems) > 0)
                                @foreach ($cartItems as $item)
                                    <div class="cart-item mb-3 pb-3 border-bottom">
                                        <div class="d-flex">
                                            <!-- Место для изображения (всегда отображается) -->
                                            <div class="me-3" style="width: 100px; min-height: 100px;">
                                                @if (isset($item['image']) && file_exists(public_path('images/products/' . $item['image'])))
                                                    <!-- Если изображение существует -->
                                                    <img src="{{ asset('images/products/' . $item['image']) }}"
                                                        alt="{{ $item['name'] }}" class="img-fluid rounded"
                                                        style="width: 100px; height: 100px; object-fit: cover;">
                                                @else
                                                    <!-- Заглушка для изображения -->
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center border"
                                                        style="width: 100px; height: 100px;">
                                                        <div class="text-center">
                                                            <i class="bi bi-image text-muted d-block"
                                                                style="font-size: 1.5rem;"></i>
                                                            <small class="text-muted mt-1">Нет фото</small>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Информация о товаре -->
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h5 class="mb-1">{{ $item['name'] }}</h5>
                                                        <p class="text-muted mb-1">Цена:
                                                            {{ number_format($item['price'], 2, ',', ' ') }} BYN</p>

                                                        <!-- Поле количества -->
                                                        <div class="d-flex align-items-center mt-2">
                                                            <label class="me-2 mb-0">Количество:</label>
                                                            <!-- Форма для ручного ввода -->
                                                            <form action="{{ route('api.handle') }}" method="POST"
                                                                class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="action"
                                                                    value="update_cart">
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $item['id'] }}">
                                                                <input type="number" name="quantity"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $item['quantity'] }}" min="0"
                                                                    style="width: 80px;" onchange="this.form.submit()">
                                                            </form>
                                                        </div>
                                                        <p class="text-muted mb-2 mt-2">Стоимость:
                                                            {{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }}
                                                            BYN</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <p class="text-muted mb-3">Корзина пуста</p>
                                    <a href="{{ route('home') }}" class="btn btn-outline-primary">Вернуться к
                                        покупкам</a>
                                </div>
                            @endif
                        </div>

                        @if (count($cartItems) > 0)
                            <!-- Total price -->
                            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                                <h4 class="mb-0 me-3"><strong>Итого:</strong></h4>
                                <h3 class="text-primary mb-0">{{ number_format($total, 2, ',', ' ') }} BYN</h3>
                            </div>

                            <!-- Contacts section -->
                            <h4>Ваши контакты</h4>
                            
                            <!-- Форма оформления заказа -->
                            <form action="{{ route('api.handle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="create_order">
                                
                                <div class="row g-2 align-items-center mb-3">
                                    <div class="col-auto">
                                        <label class="col-form-label">Ваше имя</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row g-2 align-items-center mb-3">
                                    <div class="col-auto">
                                        <label for="exampleFormControlInput1" class="col-form-label">Ваш email</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="email" name="email" class="form-control" id="exampleFormControlInput1"
                                            placeholder="name@example.com" required>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary rounded-pill mb-3 px-4 py-2">
                                        Оформить заказ
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>