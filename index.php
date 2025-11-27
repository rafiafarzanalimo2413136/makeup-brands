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


$search_query = "";
if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
    $safe = "%" . $conn->real_escape_string($search_query) . "%";


    $sql = "SELECT * FROM makeup_brands 
            WHERE name LIKE ? OR country LIKE ? OR description LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $safe, $safe, $safe);
    $stmt->execute();
    $result = $stmt->get_result();
} else {

    $result = $conn->query("SELECT * FROM makeup_brands");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Makeup Brands</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your CSS file -->
</head>
<body>

<h1>Makeup Brands</h1>

<?php if (isset($_SESSION['user_id'])): ?>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> |
    <a href="logout.php">Logout</a></p>
<?php else: ?>
    <p><a href="login.php">Login</a> |
    <a href="register.php">Register</a></p>
<?php endif; ?>

<p><a href="add_brand.php" class="add-new-btn">➕ Add New Brand</a></p>

<!-- Single Search Bar -->
<form method="GET" action="index.php">
    <input type="text" 
           name="search" 
           id="search" 
           autocomplete="off"
           placeholder="Search by name, country, or description..." 
           value="<?php echo htmlspecialchars($search_query); ?>">
    <button type="submit">Search</button>
</form>

<br>

<table border="1" cellpadding="8">
<tr>
    <th>Name</th>
    <th>Country</th>
    <th>Founded</th>
    <th>Description</th>
    <th>Website</th>
    <th>Edit</th>
    <th>Delete</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td><?php echo htmlspecialchars($row['country']); ?></td>
    <td><?php echo htmlspecialchars($row['founded_year']); ?></td>
    <td><?php echo htmlspecialchars($row['description']); ?></td>
    <td><a href="<?php echo htmlspecialchars($row['website']); ?>" target="_blank">Visit Website</a></td>
    <td><a href="edit_brand.php?id=<?php echo $row['id']; ?>">✏ Edit</a></td>
    <td><a href="delete_brand.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this brand?');">🗑 Delete</a></td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
