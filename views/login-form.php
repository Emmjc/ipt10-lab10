<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login to Your Account</h1>
    <form method="POST" action="/login">
        <label for="username_or_email">Username or Email:</label>
        <input type="text" id="username_or_email" name="username_or_email" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="/registration-form">Register here</a>.</p>
</body>
</html>
