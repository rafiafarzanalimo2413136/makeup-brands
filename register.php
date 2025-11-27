// <?php
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
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username or Email already taken!";
        exit;
    }

   
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    if ($stmt->execute()) {
        echo "Registration successful! You can now <a href='login.php'>login</a>.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}


$first_number = rand(1, 10);
$second_number = rand(1, 10);
$_SESSION['captcha_result'] = $first_number + $second_number; // Store the correct answer in session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Register</h1>

    <form method="POST" action="register.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <!-- Simple Math CAPTCHA -->
        <label for="captcha"><?php echo $first_number . " + " . $second_number . " = ?"; ?></label>
        <input type="text" id="captcha" name="captcha" required><br><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
