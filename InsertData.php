<!DOCTYPE html>
<html>
<head>
    <title>Insert Student Data</title>
    <link rel="stylesheet" href="style/insertData.css">
</head>
<body>

    <?php
// PDO database connection configuration
$host = "localhost";
$dbname = "celebratenow";
$username = "root";
$password = "Aidil020601!";

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve the submitted data
        $name = $_POST["name"];
        $dob = $_POST["dob"];
        $age = $_POST["age"];
        $address = $_POST["address"];
        $telegramID = $_POST["telegramID"];

        // Prepare the SQL statement to insert the data
        $stmt = $pdo->prepare("INSERT INTO student (StudentName, DOB, Age, Address, TelegramID) VALUES (:name, :dob, :age, :address, :telegramID)");

        // Bind the parameters with the values
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':telegramID', $telegramID);

        // Execute the prepared statement
        $stmt->execute();

        echo "<div class='message'>Student data inserted successfully.</div>";
    }
} catch (PDOException $e) {
    echo "<div class='message'>Database connection failed: " . $e->getMessage() . "</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
<div class="container">
        <h1>Insert Student Data</h1>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="StudentName">Student Name:</label>
            <input type="text" name="name" required>

            <label for="DOB">DOB (Date of Birth):</label>
            <input type="date" name="dob" required>

            <label for="Age">Age:</label>
            <input type="number" name="age" required><br>

        <label for="Address">Address:</label>
        <textarea name="address" required></textarea><br>

        <label for="TelegramID">Telegram ID:</label>
        <input type="text" name="telegramID" required><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>