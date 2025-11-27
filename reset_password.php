<?php
session_start();


require_once 'vendor/autoload.php';


$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,  
]);


$servername = "localhost";
$username = "2413136";
$password = "Jahanara50@7890";
$dbname = "db2413136";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$token = isset($_GET['token']) ? $_GET['token'] : '';


$sql = "SELECT * FROM users WHERE reset_token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Invalid or expired token!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

  
    if ($new_password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
      
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    
        $sql = "UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $token);

        if ($stmt->execute()) {
            $success_message = "Your password has been reset successfully. You can now <a href='login.php'>login</a>.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();


echo $twig->render('reset_password.html', [
    'title' => 'Reset Password',
    'error_message' => isset($error_message) ? $error_message : '',
    'success_message' => isset($success_message) ? $success_message : '',
    'token' => $token,
    'base_url' => 'https://mi-linux.wlv.ac.uk/~2413136/makeup-brands/'
]);
?>
