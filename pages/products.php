<?php

$products = [
    [
        'id' => 1,
        'name' => 'Wireless Headphones',
        'price' => 1499,
        'category' => 'Electronics',
        'icon' => '🎧'
    ],
    [
        'id' => 2,
        'name' => 'Gaming Mouse',
        'price' => 799,
        'category' => 'Gaming',
        'icon' => '🖱️'
    ],
    [
        'id' => 3,
        'name' => 'Classic T-Shirt',
        'price' => 599,
        'category' => 'Fashion',
        'icon' => '👕'
    ],
    [
        'id' => 4,
        'name' => 'Sports Shoes',
        'price' => 1999,
        'category' => 'Sports',
        'icon' => '👟'
    ],
    [
        'id' => 5,
        'name' => 'Programming Book',
        'price' => 899,
        'category' => 'Books',
        'icon' => '📚'
    ],
    [
        'id' => 6,
        'name' => 'Gaming Controller',
        'price' => 2499,
        'category' => 'Gaming',
        'icon' => '🎮'
    ]
];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shop | eShopper</title>

    <link rel="stylesheet" href="../public/css/style.css">

    <style>
        .shop-header {
            background: #fff;
            padding: 50px 6%;
            text-align: center;
        }

        .shop-header h1 {
            margin-bottom: 10px;
        }

        .products {
            padding: 40px 6%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .product {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
        }

        .product-image {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f1f1;
            font-size: 70px;
        }

        .product-info {
            padding: 20px;
        }

        .product-category {
            color: #777;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .product-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .product-btn {
            display: block;
            width: 100%;
            border: 0;
            padding: 12px;
            border-radius: 7px;
            background: #111;
            color: #fff;
            cursor: pointer;
            font-size: 15px;
        }
    </style>
</head>

<body>

<header>
    <div class="logo">🛍️ eShopper</div>

    <nav>
        <a href="../index.php">Home</a>
        <a href="products.php">Shop</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
</header>

<section class="shop-header">

    <h1>Shop on eShopper</h1>

    <p>Discover products from our marketplace.</p>

</section>

<section class="products">

<?php foreach ($products as $product): ?>

    <article class="product">

        <div class="product-image">
            <?= htmlspecialchars($product['icon']) ?>
        </div>

        <div class="product-info">

            <div class="product-category">
                <?= htmlspecialchars($product['category']) ?>
            </div>

            <div class="product-name">
                <?= htmlspecialchars($product['name']) ?>
            </div>

            <div class="product-price">
                ₹<?= number_format($product['price']) ?>
            </div>

            <button
                class="product-btn"
                onclick="alert('Cart system coming next!')"
            >
                Add to Cart
            </button>

        </div>

    </article>

<?php endforeach; ?>

</section>

<footer>
    © 2026 eShopper — Buy • Sell • Grow
</footer>

</body>
</html>
