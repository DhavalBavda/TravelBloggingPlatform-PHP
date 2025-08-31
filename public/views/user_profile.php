<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <p><b>Email:</b> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><b>Phone:</b> <?php echo htmlspecialchars($user['phone_number']); ?></p>
    <p><b>Created At:</b> <?php echo htmlspecialchars($user['created_at']); ?></p>
</body>
</html>
