<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <h1>Register a New Account</h1>
    <form method="POST" action="/register">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name"><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name"><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="password_confirm">Confirm Password:</label>
        <input type="password" id="password_confirm" name="password_confirm" required><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="/login-form">Login here</a>.</p>
</body>
</html>
