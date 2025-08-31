<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600&display=swap" rel="stylesheet">
    <title>Login</title>
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-box {
            background: #e0e0e0;
            padding: 30px;
            border-radius: 15px;
            width: 300px;
            text-align: center;
        }
        .register-box h2 {
             font-family: 'Dancing Script', cursive;
    font-size: 38px;
        }
        .register-box input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 20px;
            background: #555;
            color: #fff;
        }
        .register-box button {
            width: 95%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: #5d9c95;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .register-box p {
            margin-top: 15px;
        }
        .register-box a {
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Register</h2>
        <form method="post" action="index.php?page=auth&action=register">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="text" name="phone" placeholder="Phone" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="index.php?page=auth&action=login">Login here</a></p>
    </div>
</body>
</html>
