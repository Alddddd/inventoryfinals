<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "inventorymanagement";

try {
    // Create PDO connection
    $dsn = "mysql:host=$servername;dbname=$database;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);

    // Sanitize input
    $customerName = filter_input(INPUT_POST, 'customerName', FILTER_SANITIZE_STRING);
    $customerEmail = filter_input(INPUT_POST, 'customerEmail', FILTER_SANITIZE_EMAIL);
    $cart = json_decode($_POST['cart'], true);

    // Start transaction
    $pdo->beginTransaction();

    try {
        // Prepare the order insert query
        $sql = "INSERT INTO `order` (quantity, order_date, order_price) VALUES ";
        $values = [];
        foreach ($cart as $item) {
            $productId = (int)$item['product_id'];
            $quantity = (int)$item['quantity'];
            $price = (float)$item['price'];
            $total = (float)$item['total'];

            // Update product quantity
            $sql_update = "UPDATE product SET quantity = quantity - :quantity WHERE product_id = :productId";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([':quantity' => $quantity, ':productId' => $productId]);

            $values[] = "('$quantity', CURRENT_DATE(), '$total')";
        }
        $sql .= implode(",", $values);

        $pdo->exec($sql);

        // Commit transaction
        $pdo->commit();
        echo "Checkout successful!";
    } catch (Exception $e) {
        // Rollback transaction
        $pdo->rollBack();
        echo "Failed: " . $e->getMessage();
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
