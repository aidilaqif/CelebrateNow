<?php
session_start();

// Check if the user has exceeded the maximum login attempts
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
    echo "You have exceeded the maximum login attempts. Please try again later.";
    echo "<br>";
    echo '<form method="POST" action="reset.php">';
    echo '<input type="submit" value="Start Over">';
    echo '</form>';
    exit;
}

// Check if the form is submitted and the username and password fields are not empty
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    // Get the submitted form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the form data
    if (empty($username) || empty($password)) {
        echo "Please enter username and password.";
        exit;
    }

    // Check if the username exists in the database
    // Replace 'your_database', 'your_username', 'your_password', and 'your_table' with your actual database credentials and table name
    $conn = new PDO("mysql:host=localhost;dbname=celebratenow", 'root', 'Aidil020601!');
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Verify the password
        if ($user && $user['password'] === $password) {
            // Reset the login attempts counter
            unset($_SESSION['login_attempts']);

            // Set the user information in the session
            $_SESSION['user'] = $user;

            // Redirect to the dashboard page
            header("Location: upload.php");
            exit;
        } else {
            // If the password is invalid, increment the login attempts counter
            $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;

            echo "Invalid password.";
        }
    } else {
        // If the username is invalid, increment the login attempts counter
        $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;

        echo "Invalid username.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/login.css">

</head>

<body>
    <h1>Birthday-Wisher On The Go</h1>
    <div class="container">
        <div class ="text-column">
            <h2>Welcome!</h2>
            <p>We are here to assist you, amigo! <br>
                Wish your beloved person today to let them know how special they are. <br>
                <img src="bdaycake.jpg" alt="Birthday Cake" width="150" height="150">
            </p>
            
        </div>
        <div class="form-column">
            <h2>Login</h2>
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>