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

$brand_id = $_GET['id'];
$sql = "SELECT * FROM makeup_brands WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $brand_id);
$stmt->execute();
$brand = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $country = $_POST['country'];
    $founded_year = $_POST['founded_year'];
    $description = $_POST['description'];
    $website = $_POST['website'];

    $sql = "UPDATE makeup_brands SET name = ?, country = ?, founded_year = ?, description = ?, website = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissi", $name, $country, $founded_year, $description, $website, $brand_id);

    if ($stmt->execute()) {
        $message = "Brand updated successfully!";
    } else {
        $message = "Error updating brand.";
    }
}

$conn->close();

echo $twig->render('edit_brand.html', ['brand' => $brand, 'message' => isset($message) ? $message : '']);
?>
