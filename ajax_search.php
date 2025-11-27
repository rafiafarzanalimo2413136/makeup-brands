<?php

$servername = "localhost";
$username = "2413136";
$password = "Jahanara50@7890";
$dbname = "db2413136";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$q = isset($_GET['q']) ? $_GET['q'] : ""; 


$q = "%" . $conn->real_escape_string($q) . "%";


$sql = "SELECT name FROM makeup_brands WHERE name LIKE ? OR country LIKE ? OR description LIKE ? LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $q, $q, $q);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='suggestion' onclick='selectBrand(\"" . htmlspecialchars($row["name"]) . "\")'>" . htmlspecialchars($row["name"]) . "</div>";
    }
} else {
    echo "<div class='suggestion'>No results found</div>";
}

$stmt->close();
$conn->close();
?>
