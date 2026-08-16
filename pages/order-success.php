<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$orderId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$orderId) {
    header('Location: account.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$order = null;

try {

    $db = getDatabaseConnection();

    $stmt = $db->prepare(
        'SELECT id, total_amount, status, created_at
         FROM orders
         WHERE id = ? AND user_id = ?
         LIMIT 1'
    );

    $stmt->execute([
        $orderId,
        $_SESSION['user_id']
    ]);

    $order = $stmt->fetch();

} catch (PDOException $e) {
    $order = null;
}

if (!$order) {
    header('Location: account.php');
    exit;
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

    <title>Order Confirmed | eShopper</title>

    <link
        rel="stylesheet"
        href="../public/css/style.css"
    >

    <style>

        .success-page {
            min-height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .success-box {
            background: #fff;
            width: 100%;
            max-width: 600px;
            text-align: center;
            padding: 45px 30px;
            border-radius: 16px;
            box-shadow: 0 3px 15px rgba(0,0,0,.08);
        }

        .success-icon {
            font-size: 65px;
            margin-bottom: 15px;
        }

        .order-number {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .success-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
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

<main class="success-page">

    <div class="success-box">

        <div class="success-icon">
            🎉
        </div>

        <h1>
            Order Placed Successfully!
        </h1>

        <p>
            Thank you for shopping with eShopper.
        </p>

        <div class="order-number">

            <strong>
                Order #<?= htmlspecialchars($order['id']) ?>
            </strong>

            <br><br>

            Total:
            <strong>
                ₹<?= number_format((float)$order['total_amount']) ?>
            </strong>

            <br>

            Status:
            <?= htmlspecialchars(ucfirst($order['status'])) ?>

        </div>

        <div class="success-actions">

            <a
                href="orders.php"
                class="btn"
            >
                📦 My Orders
            </a>

            <a
                href="products.php"
                class="btn"
            >
                🛍️ Continue Shopping
            </a>

        </div>

    </div>

</main>

<footer>

    © 2026 eShopper — Buy • Sell • Grow

</footer>

</body>

</html>
