<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$products = [
    1 => [
        'name' => 'Wireless Headphones',
        'price' => 1499
    ],
    2 => [
        'name' => 'Gaming Mouse',
        'price' => 799
    ],
    3 => [
        'name' => 'Classic T-Shirt',
        'price' => 599
    ],
    4 => [
        'name' => 'Sports Shoes',
        'price' => 1999
    ],
    5 => [
        'name' => 'Programming Book',
        'price' => 899
    ],
    6 => [
        'name' => 'Gaming Controller',
        'price' => 2499
    ]
];

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

$total = 0;

foreach ($cart as $id => $quantity) {

    if (!isset($products[$id])) {
        continue;
    }

    $total += $products[$id]['price'] * $quantity;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');

    if (
        $name === '' ||
        $phone === '' ||
        $address === '' ||
        $city === '' ||
        $state === '' ||
        $pincode === ''
    ) {

        $error = 'Please fill in all delivery details.';

    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {

        $error = 'Please enter a valid 10-digit phone number.';

    } elseif (!preg_match('/^[0-9]{6}$/', $pincode)) {

        $error = 'Please enter a valid 6-digit PIN code.';

    } else {

        try {

            $db = getDatabaseConnection();

            $db->beginTransaction();

            $stmt = $db->prepare(
                'INSERT INTO orders
                (user_id, total_amount, status)
                VALUES (?, ?, ?)'
            );

            $stmt->execute([
                $_SESSION['user_id'],
                $total,
                'pending'
            ]);

            $orderId = $db->lastInsertId();

            $itemStmt = $db->prepare(
                'INSERT INTO order_items
                (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)'
            );

            foreach ($cart as $id => $quantity) {

                if (!isset($products[$id])) {
                    continue;
                }

                $itemStmt->execute([
                    $orderId,
                    $id,
                    $quantity,
                    $products[$id]['price']
                ]);
            }

            $db->commit();

            $_SESSION['cart'] = [];

            $_SESSION['last_order_id'] = $orderId;

            header(
                'Location: order-success.php?id=' .
                urlencode($orderId)
            );

            exit;

        } catch (Throwable $e) {

            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }

            $error = 'Unable to place your order right now.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Checkout | eShopper</title>

    <link
        rel="stylesheet"
        href="../public/css/style.css"
    >

    <style>

        .checkout-page {
            max-width: 1100px;
            margin: auto;
            padding: 45px 6%;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns:
                minmax(0, 1fr) 350px;
            gap: 25px;
        }

        .checkout-box {
            background: #fff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
        }

        .checkout-box h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }

        .summary-total {
            border-top: 1px solid #ddd;
            margin-top: 15px;
            padding-top: 15px;
            font-size: 21px;
            font-weight: bold;
        }

        .error {
            background: #ffe5e5;
            color: #a00000;
            padding: 13px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        @media (max-width: 750px) {

            .checkout-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<header>

    <div class="logo">
        🛍️ eShopper
    </div>

    <nav>

        <a href="../index.php">Home</a>

        <a href="products.php">Shop</a>

        <a href="cart.php">🛒 Cart</a>

        <a href="account.php">👤 Account</a>

    </nav>

</header>

<main class="checkout-page">

    <h1>🧾 Checkout</h1>

    <br>

    <?php if ($error): ?>

        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <div class="checkout-grid">

        <section class="checkout-box">

            <h2>📍 Delivery Information</h2>

            <form method="POST">

                <div class="form-group">

                    <label for="name">
                        Full Name
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        maxlength="100"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="phone">
                        Mobile Number
                    </label>

                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        inputmode="numeric"
                        maxlength="10"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="address">
                        Address
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        maxlength="500"
                        required
                    ></textarea>

                </div>

                <div class="form-group">

                    <label for="city">
                        City
                    </label>

                    <input
                        id="city"
                        name="city"
                        type="text"
                        maxlength="100"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="state">
                        State
                    </label>

                    <input
                        id="state"
                        name="state"
                        type="text"
                        maxlength="100"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="pincode">
                        PIN Code
                    </label>

                    <input
                        id="pincode"
                        name="pincode"
                        type="text"
                        inputmode="numeric"
                        maxlength="6"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn"
                    style="width:100%;border:0;cursor:pointer;"
                >
                    📦 Place Order
                </button>

            </form>

        </section>

        <aside class="checkout-box">

            <h2>🛒 Order Summary</h2>

            <?php foreach ($cart as $id => $quantity): ?>

                <?php if (!isset($products[$id])) continue; ?>

                <?php
                    $subtotal =
                        $products[$id]['price'] * $quantity;
                ?>

                <div class="summary-row">

                    <span>
                        <?= htmlspecialchars($products[$id]['name']) ?>
                        × <?= $quantity ?>
                    </span>

                    <span>
                        ₹<?= number_format($subtotal) ?>
                    </span>

                </div>

            <?php endforeach; ?>

            <div class="summary-row summary-total">

                <span>Total</span>

                <span>
                    ₹<?= number_format($total) ?>
                </span>

            </div>

            <p style="margin-top:20px;color:#777;">
                Payment will be added in a later step.
            </p>

        </aside>

    </div>

</main>

<footer>

    © 2026 eShopper — Buy • Sell • Grow

</footer>

</body>

</html>
