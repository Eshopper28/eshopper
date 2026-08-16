<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$orders = [];

try {

    $db = getDatabaseConnection();

    $stmt = $db->prepare(
        'SELECT id, total_amount, status, created_at
         FROM orders
         WHERE user_id = ?
         ORDER BY id DESC'
    );

    $stmt->execute([
        $_SESSION['user_id']
    ]);

    $orders = $stmt->fetchAll();

} catch (PDOException $e) {

    $error = 'Unable to load your orders.';
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

    <title>My Orders | eShopper</title>

    <link
        rel="stylesheet"
        href="../public/css/style.css"
    >

    <style>

        .orders-page {
            max-width: 1000px;
            margin: auto;
            padding: 45px 6%;
        }

        .order-card {
            background: #fff;
            padding: 22px;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
        }

        .order-top {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .order-id {
            font-weight: bold;
            font-size: 18px;
        }

        .order-status {
            padding: 6px 12px;
            border-radius: 20px;
            background: #eee;
            font-size: 14px;
        }

        .order-info {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        .empty-orders {
            background: #fff;
            text-align: center;
            padding: 50px 20px;
            border-radius: 12px;
        }

        .error {
            background: #ffe5e5;
            color: #a00000;
            padding: 15px;
            border-radius: 8px;
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

<main class="orders-page">

    <h1>📦 My Orders</h1>

    <br>

    <?php if (isset($error)): ?>

        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php elseif (empty($orders)): ?>

        <div class="empty-orders">

            <h2>No orders yet</h2>

            <p>
                Your purchases will appear here.
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

        <?php foreach ($orders as $order): ?>

            <article class="order-card">

                <div class="order-top">

                    <div class="order-id">

                        Order #<?= htmlspecialchars($order['id']) ?>

                    </div>

                    <div class="order-status">

                        <?= htmlspecialchars(
                            ucfirst($order['status'])
                        ) ?>

                    </div>

                </div>

                <div class="order-info">

                    <div>

                        <strong>Total</strong>

                        <br>

                        ₹<?= number_format(
                            (float)$order['total_amount']
                        ) ?>

                    </div>

                    <div>

                        <strong>Date</strong>

                        <br>

                        <?= htmlspecialchars(
                            $order['created_at']
                        ) ?>

                    </div>

                </div>

            </article>

        <?php endforeach; ?>

    <?php endif; ?>

</main>

<footer>

    © 2026 eShopper — Buy • Sell • Grow

</footer>

</body>

</html>
