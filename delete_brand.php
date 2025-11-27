<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$servername = "localhost";
$username = "2413136"; 
$password = "Jahanara50@7890"; 
$dbname = "db2413136"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['id'])) {
    $brand_id = $_GET['id'];


    $sql = "DELETE FROM makeup_brands WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $brand_id);

    if ($stmt->execute()) {
       
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting brand: " . $stmt->error;
    }
} else {
    echo "No brand ID provided!";
}

$stmt->close();
$conn->close();
?>
