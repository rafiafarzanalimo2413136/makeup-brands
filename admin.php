<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


if ($_SESSION['is_admin'] !== 1) {
    echo "You do not have permission to access this page.";
    exit;
}


$servername = "localhost";
$username = "2413136";
$password = "Jahanara50@7890";
$dbname = "db2413136";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM makeup_brands";
$result = $conn->query($sql);

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <p><a href="add_brand.php">➕ Add New Brand</a> | <a href="logout.php">Logout</a></p>

    <table border="1" cellpadding="8">
        <tr>
            <th>Name</th>
            <th>Country</th>
            <th>Founded</th>
            <th>Description</th>
            <th>Website</th>
            <th>Actions</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['country']; ?></td>
            <td><?php echo $row['founded_year']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><a href="<?php echo $row['website']; ?>" target="_blank">Visit</a></td>
            <td>
                <a href="edit_brand.php?id=<?php echo $row['id']; ?>">✏ Edit</a> |
                <a href="delete_brand.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this brand?');">🗑 Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
