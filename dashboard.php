<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$host = "localhost";
$user = "momer3";
$pass = "momer3";
$dbname = "momer3";

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch properties for the logged-in seller
$sql = "SELECT * FROM Properties WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$properties = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<div class="dashboard">
        <h1>Welcome to your Dashboard</h1>
        <form action="logout.php" method="POST">
            <button type="submit" class="logout-button">Logout</button>
        </form>

<div class="property-list">
    <!-- Always show the Add Property Card at the top -->
    <a href="add_property_form.php" class="property-card add-property-card" style="z-index: 10; position: relative; background-color: grey;">
    <div class="add-property-symbol" style="font-size: 5em; color: #439e81; text-align: center;">
        <span>+</span>
    </div>
    <div class="property-details">
        <h2>Add a new property</h2>
    </div>
</a>

    <?php foreach ($properties as $property): ?>
        <a href="property_details.php?propertyID=<?php echo $property['propertyID']; ?>" class="property-card" style="background-image: url('<?php echo $property['imagePath']; ?>');">
            <div class="property-details">
                <h2><?php echo htmlspecialchars($property['location']); ?></h2>
                <p class="price">$<?php echo number_format($property['price'], 2); ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>

</body>
</html>