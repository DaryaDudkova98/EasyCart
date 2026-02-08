<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EasyCart - Канцтовары</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

</head>

<body>
    <div class="container mt-4 d-flex justify-content-between align-items-center">
        <h3 class="text-dark m-0">Тестовое задание Дудкова Дарья</h3>

        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary rounded-circle position-relative p-2"
            style="width: 48px; height: 48px;">
            <i class="bi bi-cart fs-5"></i>

            @php
                $cartCount = 0;
                $cart = session('cart', []);
                foreach ($cart as $item) {
                    $cartCount += $item['quantity'] ?? 0;
                }
            @endphp

            @if ($cartCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
    </div>

    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

            @foreach ($products as $product)
                @php
                    $productInfo = $productData[$product['id']] ?? [];
                @endphp
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <!-- Изображение товара -->
                        <div class="bg-light d-flex align-items-center justify-content-center"
                            style="height: 200px; overflow: hidden;">
                            @if ($productInfo['has_image'] ?? false)
                                <img src="{{ asset($productInfo['image_path']) }}" alt="{{ $product['name'] }}"
                                    class="img-fluid p-3" style="max-height: 100%; object-fit: contain;">
                            @else
                                <div class="text-center text-muted">
                                    <i class="bi bi-image fs-1"></i>
                                    <div class="mt-2 small">Нет изображения</div>
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fs-6 mb-2" style="min-height: 48px;">
                                {{ $product['name'] }}
                            </h5>

                            <p class="card-text text-success fw-bold mb-3">
                                {{ number_format($product['price'], 2, ',', ' ') }} BYN
                            </p>

                            <form action="{{ route('api.handle') }}" method="POST" class="mt-auto">
                                @csrf
                                <input type="hidden" name="action" value="add_to_cart">
                                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">
                                    Добавить в корзину
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>


    </div>

    <!-- Bootstrap JS для работы алертов -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
