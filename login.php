<?php
session_start();
$servername = "localhost";
$username = "2413136"; 
$password = "Jahanara50@7890"; 
$dbname = "db2413136"; 


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    if ($_POST['captcha'] != $_SESSION['captcha_result']) {
        echo "Incorrect CAPTCHA answer. Please try again!";
        exit;
    }

  
    $username = $_POST['username'];
    $password = $_POST['password'];

 
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

   
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php"); 
    } else {
        echo "Invalid login credentials!";
    }

    $stmt->close();
    $conn->close();
}


$first_number = rand(1, 10);
$second_number = rand(1, 10);
$_SESSION['captcha_result'] = $first_number + $second_number; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Login</h1>

    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <!-- Simple Math CAPTCHA -->
        <label for="captcha"><?php echo $first_number . " + " . $second_number . " = ?"; ?></label>
        <input type="text" id="captcha" name="captcha" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
