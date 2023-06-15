<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Birthday Message</title>
    <link rel="stylesheet" href="style/bot.css">
</head>
<body>
    <div class="container">
        <h1>Send Birthday Message</h1>

        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Date of Birth</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Telegram ID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // PDO database connection configuration
                $host = "localhost";
                $dbname = "celebratenow";
                $username = "root";
                $password = "Aidil020601!";

                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die("Connection failed: " . $e->getMessage());
                }

                $query = "SELECT * FROM student";
                $stmt = $pdo->prepare($query);
                $stmt->execute();

                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($students as $student) {
                    $studentName = $student['StudentName'];
                    $studentBirthday = $student['DOB'];
                    $studentAge = $student['Age'];
                    $studentAddress = $student['Address'];
                    $telegramId = $student['TelegramID'];

                    echo "<tr>";
                    echo "<td>$studentName</td>";
                    echo "<td>$studentBirthday</td>";
                    echo "<td>$studentAge</td>";
                    echo "<td>$studentAddress</td>";
                    echo "<td>$telegramId</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <form action="" method="post">
            <input type="submit" name="submit" value="Send Birthday Message">
        </form>
    </div>

    <?php
    if(isset($_POST['submit'])){
        $apiToken = "6203530879:AAEsj9RKjtdkgc7tU5EPeuaOwY6BYOozKbE";
        
        foreach ($students as $student) {
            $telegramId = $student['TelegramID'];
            $studentName = $student['StudentName'];
            $studentBirthday = $student['DOB'];
            $studentAge = $student['Age'];

            $message = 'Happy Birthday '.$studentName.'!! May your '.$studentAge.' years of your life be a meaningful one :)';

            $data = [
                'chat_id' => '@celebratenowchannel',
                'text' => $message
            ];

            $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data));
        }
    }
    ?>
</body>
</html>
