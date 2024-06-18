<?php
include('server.php');

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $quant = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);

    $stmt = $db->prepare("UPDATE product SET product_name = :name, price = :price, quantity = :quant WHERE product_id = :id");
    $stmt->execute([':name' => $name, ':price' => $price, ':quant' => $quant, ':id' => $id]);

    header("Location: table.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM product WHERE product_id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();

    if ($row) {
        $name = $row['product_name'];
        $price = $row['price'];
        $quant = $row['quantity'];
    } else {
        echo "No results!";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Item</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 50%;
        margin: 50px auto;
        background: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        text-align: center;
        color: #333;
    }
    form {
        display: flex;
        flex-direction: column;
    }
    label {
        margin: 10px 0 5px;
        font-weight: bold;
    }
    input[type="text"], input[type="number"] {
        padding: 10px;
        font-size: 16px;
        width: 100%;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    input[type="submit"] {
        padding: 10px;
        font-size: 16px;
        background: #5cb85c;
        border: none;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background: #4cae4c;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Edit Item</h2>
    <form method="post" action="edit.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>"/>
        <label for="product_name">Item Name <em>*</em></label>
        <input type="text" name="product_name" value="<?php echo htmlspecialchars($name); ?>" required/>
        
        <label for="price">Price <em>*</em></label>
        <input type="number" name="price" value="<?php echo htmlspecialchars($price); ?>" step="0.01" required/>
        
        <label for="quantity">Quantity <em>*</em></label>
        <input type="number" name="quantity" value="<?php echo htmlspecialchars($quant); ?>" required/>

        <input type="submit" name="submit" value="Edit Records">
    </form>
</div>

</body>
</html>
