<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/css/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600&display=swap" rel="stylesheet">
    <title>Login</title>
    <title>Register</title>
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
        <p>Already have an account? Login <a href="index.php?page=auth&action=login">here</a></p>
    </div>
</body>
</html>
