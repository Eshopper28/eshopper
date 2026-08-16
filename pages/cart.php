<?php

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$products = [
    1 => [
        'name' => 'Wireless Headphones',
        'price' => 1499,
        'icon' => '🎧'
    ],
    2 => [
        'name' => 'Gaming Mouse',
        'price' => 799,
        'icon' => '🖱️'
    ],
    3 => [
        'name' => 'Classic T-Shirt',
        'price' => 599,
        'icon' => '👕'
    ],
    4 => [
        'name' => 'Sports Shoes',
        'price' => 1999,
        'icon' => '👟'
    ],
    5 => [
        'name' => 'Programming Book',
        'price' => 899,
        'icon' => '📚'
    ],
    6 => [
        'name' => 'Gaming Controller',
        'price' => 2499,
        'icon' => '🎮'
    ]
];

/* Add product */
if (isset($_GET['add'])) {

    $id = (int) $_GET['add'];

    if (isset($products[$id])) {
        $_SESSION['cart'][$id] =
            ($_SESSION['cart'][$id] ?? 0) + 1;
    }

    header('Location: cart.php');
    exit;
}

/* Remove product */
if (isset($_GET['remove'])) {

    $id = (int) $_GET['remove'];

    unset($_SESSION['cart'][$id]);

    header('Location: cart.php');
    exit;
}

/* Clear cart */
if (isset($_GET['clear'])) {

    $_SESSION['cart'] = [];

    header('Location: cart.php');
    exit;
}

$total = 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Cart | eShopper</title>

    <link
        rel="stylesheet"
        href="../public/css/style.css"
    >

    <style>

        .cart-page {
            padding: 50px 6%;
            max-width: 1000px;
            margin: auto;
        }

        .cart-item {
            background: #fff;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;

            display: flex;
            align-items: center;
            gap: 20px;
        }

        .cart-icon {
            font-size: 45px;
        }

        .cart-info {
            flex: 1;
        }

        .cart-name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .cart-price {
            color: #555;
        }

        .remove {
            color: #d00;
            text-decoration: none;
        }

        .cart-total {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            margin-top: 25px;
        }

        .cart-total h2 {
            margin-bottom: 20px;
        }

        .cart-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .empty-cart {
            background: #fff;
            text-align: center;
            padding: 60px 20px;
            border-radius: 12px;
        }

    </style>

</head>

<body>

<header>

    <div class="logo">
        🛍️ eShopper
    </div>

    <nav>

        <a href="../index.php">
            Home
        </a>

        <a href="products.php">
            Shop
        </a>

        <a href="cart.php">
            🛒 Cart
        </a>

        <a href="login.php">
            Login
        </a>

    </nav>

</header>

<main class="cart-page">

    <h1>🛒 Your Cart</h1>

    <br>

<?php if (empty($_SESSION['cart'])): ?>

    <div class="empty-cart">

        <h2>Your cart is empty</h2>

        <p>
            Discover something you love and add it to your cart.
        </p>

        <br>

        <a
            href="products.php"
            class="btn"
        >
            Start Shopping
        </a>

    </div>

<?php else: ?>

<?php foreach ($_SESSION['cart'] as $id => $quantity): ?>

    <?php

        if (!isset($products[$id])) {
            continue;
        }

        $product = $products[$id];

        $subtotal =
            $product['price'] * $quantity;

        $total += $subtotal;

    ?>

    <div class="cart-item">

        <div class="cart-icon">
            <?= htmlspecialchars($product['icon']) ?>
        </div>

        <div class="cart-info">

            <div class="cart-name">
                <?= htmlspecialchars($product['name']) ?>
            </div>

            <div class="cart-price">

                ₹<?= number_format($product['price']) ?>

                × <?= $quantity ?>

                =
                ₹<?= number_format($subtotal) ?>

            </div>

        </div>

        <a
            class="remove"
            href="cart.php?remove=<?= $id ?>"
        >
            Remove
        </a>

    </div>

<?php endforeach; ?>

    <div class="cart-total">

        <h2>
            Total:
            ₹<?= number_format($total) ?>
        </h2>

        <div class="cart-actions">

            <a
                href="products.php"
                class="btn"
            >
                Continue Shopping
            </a>

            <a
                href="cart.php?clear=1"
                class="btn"
            >
                Clear Cart
            </a>

        </div>

    </div>

<?php endif; ?>

</main>

<footer>

    © 2026 eShopper — Buy • Sell • Grow

</footer>

</body>

</html>
