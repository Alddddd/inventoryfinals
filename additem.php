<?php 
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('location: login.php');
    exit();
}

// Connect to the database using PDO
try {
    $db = new PDO('mysql:host=localhost;dbname=inventorymanagement;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Failed to connect to MySQL: " . $e->getMessage();
    exit();
}

// Add item
if (isset($_POST['add'])) {
    // Receive all input values from the form
    $item_name = $_POST['product_name'];
    $item_price = $_POST['price'];
    $quant = $_POST['quant'];

    // Prepare and execute the query using PDO
    $query = "INSERT INTO product (product_name, price, quantity) VALUES (:item_name, :item_price, :quant)";
    $stmt = $db->prepare($query);

    // Bind parameters to prevent SQL injection
    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':item_price', $item_price);
    $stmt->bindParam(':quant', $quant);

    if ($stmt->execute()) {
        echo "<script>alert('Successfully stored');</script>";
    } else {
        echo "<script>alert('Something went wrong!!!');</script>";
    }

    header('location: table.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Item</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
</head>
<body>
    <form method="POST" class="form-inline" action="additem.php">
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" class="form-control" name="product_name" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="text" class="form-control" name="price" required>
        </div>
        <div class="form-group">
            <label for="quant">Quantity</label>
            <input type="number" class="form-control" name="quant" id="quant" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary" name="add">Add Item</button>
    </form>

    <div>
        <a href="table.php">Home</a>
    </div>
</body>
</html>
