<?php

session_start();

require_once __DIR__ . '/../config/database.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    header('Location: account.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validate name
    if ($name === '') {
        $error = 'Please enter your full name.';
    }

    // Validate email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    }

    // Validate password
    elseif (strlen($password) < 8) {
        $error = 'Password must contain at least 8 characters.';
    }

    // Confirm password
    elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    }

    else {

        try {

            $db = getDatabaseConnection();

            // Check whether email already exists
            $check = $db->prepare(
                'SELECT id FROM users WHERE email = ? LIMIT 1'
            );

            $check->execute([$email]);

            if ($check->fetch()) {

                $error = 'An account with this email already exists.';

            } else {

                // Secure password hash
                $passwordHash = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                // Create account
                $stmt = $db->prepare(
                    'INSERT INTO users
                    (name, email, password_hash, role)
                    VALUES (?, ?, ?, ?)'
                );

                $stmt->execute([
                    $name,
                    $email,
                    $passwordHash,
                    'customer'
                ]);

                // Get newly created user ID
                $userId = $db->lastInsertId();

                // Automatically log the user in
                session_regenerate_id(true);

                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = 'customer';

                // Send user to account page
                header('Location: account.php');
                exit;
            }

        } catch (PDOException $e) {

            error_log(
                'eShopper registration error: ' .
                $e->getMessage()
            );

            $error = 'We could not create your account right now. Please try again.';
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
            min-height: 75vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            box-sizing: border-box;
        }

        .auth-box {
            width: 100%;
            max-width: 450px;
            background: #fff;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .08);
            box-sizing: border-box;
        }

        .auth-box h1 {
            margin: 0 0 10px;
        }

        .auth-description {
            color: #666;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            outline: none;
        }

        .form-group input:focus {
            border-color: #777;
        }

        .form-submit {
            width: 100%;
            border: 0;
            cursor: pointer;
            font-size: 16px;
            padding: 13px;
        }

        .message {
            padding: 13px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .error {
            background: #ffe5e5;
            color: #a00000;
        }

        .password-help {
            display: block;
            margin-top: 6px;
            font-size: 13px;
            color: #777;
        }

        .login-link {
            text-align: center;
            margin-top: 22px;
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

<main class="auth-page">

    <div class="auth-box">

        <h1>
            Create Account
        </h1>

        <p class="auth-description">
            Join eShopper and start shopping.
        </p>

        <?php if ($error): ?>

            <div class="message error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST" autocomplete="on">

            <div class="form-group">

                <label for="name">
                    Full Name
                </label>

                <input
                    id="name"
                    name="name"
                    type="text"
                    maxlength="100"
                    autocomplete="name"
                    value="<?= htmlspecialchars($name ?? '') ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    id="email"
                    name="email"
                    type="email"
                    maxlength="150"
                    autocomplete="email"
                    value="<?= htmlspecialchars($email ?? '') ?>"
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
                    autocomplete="new-password"
                    required
                >

                <span class="password-help">
                    Minimum 8 characters.
                </span>

            </div>

            <div class="form-group">

                <label for="confirm_password">
                    Confirm Password
                </label>

                <input
                    id="confirm_password"
                    name="confirm_password"
                    type="password"
                    minlength="8"
                    autocomplete="new-password"
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

        <div class="login-link">

            Already have an account?

            <a href="login.php">
                Log in
            </a>

        </div>

    </div>

</main>

<footer>

    © 2026 eShopper — Buy • Sell • Grow

</footer>

</body>

</html>
