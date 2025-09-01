<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form method="post" action="index.php?page=auth&action=login" id = "loginForm">
            <input type="text" name="email" placeholder="Email or Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>New user? Register <a href="index.php?page=auth&action=register">here</a></p>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('loginForm');
            if (form) form.reset();
        });
    </script>
</body>
</html>
