<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eShopper | Buy & Sell</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #222;
        }

        header {
            background: #111;
            color: white;
            padding: 18px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
        }

        .hero {
            text-align: center;
            padding: 80px 20px;
            background: white;
        }

        .hero h1 {
            font-size: 42px;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 18px;
            color: #666;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            background: #111;
            color: white;
            padding: 13px 25px;
            border-radius: 6px;
            text-decoration: none;
        }

        .categories {
            padding: 45px 20px;
            text-align: center;
        }

        .categories h2 {
            margin-bottom: 25px;
        }

        .category-box {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            max-width: 900px;
            margin: auto;
        }

        .category {
            background: white;
            padding: 25px 10px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        footer {
            margin-top: 40px;
            background: #111;
            color: white;
            text-align: center;
            padding: 25px;
        }

        @media (max-width: 600px) {
            header {
                flex-direction: column;
                gap: 12px;
            }

            nav a {
                margin: 0 6px;
            }

            .hero h1 {
                font-size: 32px;
            }
        }
    </style>
</head>

<body>

<header>
    <div class="logo">🛍️ eShopper</div>

    <nav>
        <a href="#">Home</a>
        <a href="#">Shop</a>
        <a href="#">Sell</a>
        <a href="#">Login</a>
    </nav>
</header>

<section class="hero">
    <h1>Welcome to eShopper</h1>
    <p>Buy what you love. Sell what you don't.</p>
    <a href="#" class="btn">Start Shopping</a>
</section>

<section class="categories">
    <h2>Explore Categories</h2>

    <div class="category-box">
        <div class="category">📱 Electronics</div>
        <div class="category">👕 Fashion</div>
        <div class="category">🏠 Home</div>
        <div class="category">🎮 Gaming</div>
        <div class="category">📚 Books</div>
        <div class="category">⚽ Sports</div>
    </div>
</section>

<footer>
    <p>© 2026 eShopper. All rights reserved.</p>
</footer>

</body>
</html>
