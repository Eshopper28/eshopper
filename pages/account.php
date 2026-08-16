<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$user = null;

try {
    $db = getDatabaseConnection();

    $stmt = $db->prepare(
        'SELECT id, name, email, role, created_at
         FROM users
         WHERE id = ?
         LIMIT 1'
    );

    $stmt->execute([$_SESSION['user_id']]);

    $user = $stmt->fetch();

    if (!$user) {
        session_destroy();
        header('Location: login.php');
        exit;
    }

} catch (PDOException $e) {
    $error = 'Unable to load your account.';
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

    <title>My Account | eShopper</title>

    <link
        rel="stylesheet"
        href="../public/css/style.css"
    >

    <style>

        .account-page {
            max-width: 1000px;
            margin: auto;
            padding: 50px 6%;
        }

        .account-header {
            background: #111;
            color: #fff;
            padding: 30px;
            border-radius: 14px;
            margin-bottom: 25px;
        }

        .account-header h1 {
            margin-bottom: 8px;
        }

        .account-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .account-card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
        }

        .account-card h2 {
            margin-bottom: 10px;
        }

        .account-card p {
            color: #666;
            margin-bottom: 15px;
        }

        .account-card a {
            text-decoration: none;
        }

        .profile {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .profile-row {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .profile-row:last-child {
            border-bottom: 0;
        }

        .profile-label {
            font-weight: bold;
        }

        .logout {
            display: inline-block;
            margin-top: 20px;
            color: #c00;
            text-decoration: none;
            font-weight: bold;
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

        <a href="account.php">
            👤 Account
        </a>

    </nav>

</header>

<main class="account-page">

<?php if (isset($error)): ?>

    <div class="profile">
        <?= htmlspecialchars($error) ?>
    </div>

<?php else: ?>

    <section class="account-header">

        <h1>
            Welcome, <?= htmlspecialchars($user['name']) ?> 👋
        </h1>

        <p>
            Manage your eShopper account.
        </p>

    </section>

    <section class="profile">

        <h2>👤 Profile</h2>

        <div class="profile-row">

            <span class="profile-label">
                Name:
            </span>

            <?= htmlspecialchars($user['name']) ?>

        </div>

        <div class="profile-row">

            <span class="profile-label">
                Email:
            </span>

            <?= htmlspecialchars($user['email']) ?>

        </div>

        <div class="profile-row">

            <span class="profile-label">
                Account type:
            </span>

            <?= htmlspecialchars(ucfirst($user['role'])) ?>

        </div>

    </section>

    <section class="account-grid">

        <div class="account-card">

            <h2>📦 My Orders</h2>

            <p>
                View your purchases and order status.
            </p>

            <a href="orders.php" class="btn">
                View Orders
            </a>

        </div>

        <div class="account-card">

            <h2>🛒 My Cart</h2>

            <p>
                Continue shopping and checkout.
            </p>

            <a href="cart.php" class="btn">
                Open Cart
            </a>

        </div>

        <div class="account-card">

            <h2>🛍️ Shop</h2>

            <p>
                Discover products from eShopper sellers.
            </p>

            <a href="products.php" class="btn">
                Start Shopping
            </a>

        </div>

    </section>

    <a
        class="logout"
        href="logout.php"
    >
        🚪 Logout
    </a>

<?php endif; ?>

</main>

<footer>

    © 2026 eShopper — Buy • Sell • Grow

</footer>

</body>

</html>
