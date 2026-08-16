<?php

session_start();

require_once __DIR__ . '/../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {

        $error = 'Please enter your email and password.';

    } else {

        try {

            $db = getDatabaseConnection();

            $stmt = $db->prepare(
                'SELECT id, name, email, password_hash, role
                 FROM users
                 WHERE email = ?
                 LIMIT 1'
            );

            $stmt->execute([$email]);

            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {

                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                header('Location: ../index.php');
                exit;

            } else {

                $error = 'Invalid email or password.';
            }

        } catch (PDOException $e) {

            $error = 'Unable to connect to the database right now.';
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

    <title>Login | eShopper</title>

    <link
        rel="stylesheet"
        href="../public/css/style.css"
    >

    <style>

        .auth-page {
            min-height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .auth-box {
            width: 100%;
            max-width: 450px;
            background: #fff;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 3px 15px rgba(0,0,0,.08);
        }

        .auth-box h1 {
            margin-bottom: 10px;
        }

        .auth-box p {
            color: #666;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 16px;
        }

        .form-submit {
            width: 100%;
            border: 0;
            cursor: pointer;
            font-size: 16px;
        }

        .message {
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 7px;
            background: #ffe5e5;
            color: #a00000;
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
        <a href="register.php">Register</a>
    </nav>

</header>

<main class="auth-page">

    <div class="auth-box">

        <h1>Welcome Back</h1>

        <p>Log in to your eShopper account.</p>

        <?php if ($error): ?>

            <div class="message">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    id="email"
                    name="email"
                    type="email"
                    required
                >

            </div>

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                >

            </div>

            <button
                class="btn form-submit"
                type="submit"
            >
                Login
            </button>

        </form>

        <br>

        <p>
            Don't have an account?
            <a href="register.php">Create one</a>
        </p>

    </div>

</main>

<footer>
    © 2026 eShopper — Buy • Sell • Grow
</footer>

</body>

</html>
