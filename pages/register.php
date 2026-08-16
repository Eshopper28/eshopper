<?php

session_start();

require_once __DIR__ . '/../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must contain at least 8 characters.';
    } else {

        try {

            $db = getDatabaseConnection();

            $check = $db->prepare(
                'SELECT id FROM users WHERE email = ? LIMIT 1'
            );

            $check->execute([$email]);

            if ($check->fetch()) {

                $error = 'An account with this email already exists.';

            } else {

                $passwordHash = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $stmt = $db->prepare(
                    'INSERT INTO users (name, email, password_hash, role)
                     VALUES (?, ?, ?, ?)'
                );

                $stmt->execute([
                    $name,
                    $email,
                    $passwordHash,
                    'customer'
                ]);

                $success = 'Account created successfully. You can now log in.';
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

    <title>Create Account | eShopper</title>

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
        }

        .error {
            background: #ffe5e5;
            color: #a00000;
        }

        .success {
            background: #e5ffe9;
            color: #08751c;
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
        <a href="login.php">Login</a>
    </nav>

</header>

<main class="auth-page">

    <div class="auth-box">

        <h1>Create Account</h1>

        <p>Join eShopper and start shopping.</p>

        <?php if ($error): ?>

            <div class="message error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <?php if ($success): ?>

            <div class="message success">
                <?= htmlspecialchars($success) ?>
            </div>

        <?php endif; ?>

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

                <label for="email">
                    Email
                </label>

                <input
                    id="email"
                    name="email"
                    type="email"
                    maxlength="150"
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
                    minlength="8"
                    required
                >

            </div>

            <button
                class="btn form-submit"
                type="submit"
            >
                Create Account
            </button>

        </form>

        <br>

        <p>
            Already have an account?
            <a href="login.php">Log in</a>
        </p>

    </div>

</main>

<footer>
    © 2026 eShopper — Buy • Sell • Grow
</footer>

</body>

</html>
