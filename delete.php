<?php
try {
    $dsn = 'mysql:host=localhost;dbname=inventorymanagement;charset=utf8mb4';
    $username = 'root';
    $password = '';

    // Create PDO connection
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Failed to connect to MySQL: " . $e->getMessage();
    exit();
}
?>
<?php

if (isset($_GET['id'])) {
    try {
        $productId = (int)$_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM product WHERE product_id = :id");
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);

        $result = $stmt->execute();

        if ($result) {
            echo "success";
        }

        header("Location: table.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
