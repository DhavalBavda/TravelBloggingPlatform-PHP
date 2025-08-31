<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: #e0e0e0;
            padding: 30px;
            border-radius: 15px;
            width: 300px;
            text-align: center;
        }
        .login-box h2 {
            font-family: 'Dancing Script', cursive;
    font-size: 38px;
        }
        .login-box input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 20px;
            background: #555;
            color: #fff;
        }
        .login-box button {
            width: 95%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: #5d9c95;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .login-box p {
            margin-top: 15px;
        }
        .login-box a {
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form method="post" action="index.php?page=auth&action=login">
            <input type="text" name="email" placeholder="Email or Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>New user? <a href="index.php?page=auth&action=register">Register here</a></p>
    </div>
</body>
</html>
