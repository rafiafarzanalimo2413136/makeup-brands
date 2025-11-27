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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
     
        $token = bin2hex(random_bytes(50)); 
        $reset_link = "https://mi-linux.wlv.ac.uk/~2413136/makeup-brands/reset_password.php?token=" . $token;

        
        $sql = "UPDATE users SET reset_token = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

      
        $subject = "Password Reset Link";
        $message = "Click on the following link to reset your password: " . $reset_link;
        $headers = "From: no-reply@example.com"; 

        if (mail($email, $subject, $message, $headers)) {
            echo "A password reset link has been sent to your email address.";
        } else {
            echo "Failed to send email. Please try again later.";
        }
    } else {
        echo "Email address not found!";
    }

    $stmt->close();
    $conn->close();
}


echo $twig->render('forgot_password.html', [
    'title' => 'Forgot Password Page',
    'base_url' => 'https://mi-linux.wlv.ac.uk/~2413136/makeup-brands/'
]);
?>
