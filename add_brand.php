<?php
session_start();
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, ['cache' => false]);


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "2413136";
$password = "Jahanara50@7890";
$dbname = "db2413136";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $name = $_POST['name'];
    $country = $_POST['country'];
    $founded_year = $_POST['founded_year'];
    $description = $_POST['description'];
    $website = $_POST['website'];

  
    $sql = "INSERT INTO makeup_brands (name, country, founded_year, description, website) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiss", $name, $country, $founded_year, $description, $website);

    if ($stmt->execute()) {
        $message = "Brand added successfully!";
    } else {
        $message = "Error adding brand.";
    }
}

$conn->close();

echo $twig->render('add_brand.html', ['message' => isset($message) ? $message : '']);
?>
