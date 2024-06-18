<?php
session_start();

$errors = [];

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initializing variables with empty values
$first_name = "";
$last_name = "";
$username = "";
$email = "";
$password_1 = "";
$password_2 = "";
$mobile = "";
$errors = array(
    'username' => '',
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'password_1' => '',
    'password_2' => '',
    'mobile' => '',
    'general' => ''
);

// Connect to the database using PDO
try {
    $db = new PDO('mysql:host=localhost;dbname=inventorymanagement;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Failed to connect to MySQL: " . $e->getMessage();
    exit();
}

// Process AJAX requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['action']) && $_POST['action'] == 'checkQuantity') {
        $productId = $_POST['productId'];

        try {
            $stmt = $db->prepare("SELECT quantity FROM product WHERE product_id = :productId");
            $stmt->execute([':productId' => $productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'quantity' => $product['quantity']]);
                exit();
            } else {
                $errors['message'] = 'Product not found';
            }
        } catch (PDOException $e) {
            $errors['message'] = 'Error checking quantity: ' . $e->getMessage();
        }
    }
}



// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['check_sales'])) {
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];

        try {
            // Log the received dates
            error_log("Received dates: Start - $startDate, End - $endDate");

            // Use the correct column names here
            $stmt = $db->prepare("SELECT * FROM sales WHERE created_at BETWEEN :startDate AND :endDate");
            $stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
            $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
              // Calculate total order price
              $totalOrderPrice = 0;
              foreach ($sales as $sale) {
                  $totalOrderPrice += $sale['price'];
              }

            // Log the sales data
            error_log("Sales data: " . json_encode($sales));

            echo json_encode(['success' => true, 'sales' => $sales]);
        } catch (Exception $e) {
            // Log the error
            error_log("Error fetching sales: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error fetching sales: ' . $e->getMessage()]);
        }
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // REGISTER USER
    if (isset($_POST['reg_user'])) {
        // Receive all input values from the form
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password_1 = $_POST['password_1'];
        $password_2 = $_POST['password_2'];
        $mobile = trim($_POST['mobile']);
        $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : 'user';// Default to 'user' if not specified

        // Form validation: ensure that the form is correctly filled
        if (empty($username)) { $errors['username'] = "Username is required"; }
        if (empty($first_name)) { $errors['first_name'] = "First Name is required"; }
        if (empty($last_name)) { $errors['last_name'] = "Last Name is required"; }
        if (empty($email)) { $errors['email'] = "Email is required"; }
        if (empty($password_1)) { $errors['password_1'] = "Password is required"; }
        if (empty($mobile)) { $errors['mobile'] = "Mobile is required"; }
        if ($password_1 != $password_2) {
            $errors['password_2'] = "The two passwords do not match";
        }

        // First check the database to make sure a user does not already exist with the same username and/or email
        $stmt = $db->prepare("SELECT * FROM register WHERE username = :username OR email = :email LIMIT 1");
        $stmt->execute([':username' => $username, ':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) { // if user exists
            if ($user['username'] === $username) {
                $errors['username'] = "Username already exists";
            }
            if ($user['email'] === $email) {
                $errors['email'] = "Email already exists";
            }
        }

        // Finally, register the user if there are no errors in the form
        if (empty(array_filter($errors))) {
            $password = password_hash($password_1, PASSWORD_BCRYPT); // Encrypt the password before saving in the database
            $stmt = $db->prepare("INSERT INTO register (username, first_name, last_name, email, password, mobile, user_type) VALUES (:username, :first_name, :last_name, :email, :password, :mobile, :user_type)");
            $stmt->execute([
                ':username' => $username,
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':email' => $email,
                ':password' => $password,
                ':mobile' => $mobile,
                ':user_type' => $user_type
            ]);

            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";

            // Redirect based on user_type
            if ($user_type === 'admin') {
                header('location: login.php');
            } else {
                header('location: login.php');
            }

            exit(); // Ensure to exit after redirection
        }
    }

    // LOGIN USER
    if (isset($_POST['login_user'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (empty($username)) {
            $errors['username'] = "Username is required";
        }
        if (empty($password)) {
            $errors['password_1'] = "Password is required";
        }

        if (empty(array_filter($errors))) {
            $stmt = $db->prepare("SELECT * FROM register WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['user_type'] = $user['user_type']; // Store user type in session
                $_SESSION['success'] = "You are now logged in";

                if ($user['user_type'] === 'admin') {
                    header('location: index1.php');
                } else {
                    header('location: index2.php');
                }
                exit(); // Ensure to exit after redirection
            } else {
                $errors['general'] = "Wrong username/password combination";
            }
        }
    }

    // CHECKOUT
    if (isset($_POST['checkout'])) {
        $customerName = $_POST['customerName'];
        $customerEmail = $_POST['customerEmail'];
        $cart = json_decode($_POST['cart'], true);
    
        // Start transaction
        $db->beginTransaction();
    
        try {
            // Insert customer into the database if not exists
            $stmt = $db->prepare("SELECT customer_id FROM customers WHERE email = :customerEmail");
            $stmt->execute([':customerEmail' => $customerEmail]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($customer) {
                $customerId = $customer['customer_id'];
            } else {
                $stmt = $db->prepare("INSERT INTO customers (name, email) VALUES (:customerName, :customerEmail)");
                $stmt->execute([':customerName' => $customerName, ':customerEmail' => $customerEmail]);
                $customerId = $db->lastInsertId();
            }
    
            // Insert into transactions table
            $stmt = $db->prepare("INSERT INTO transactions (customer_id, created_at, total_price) VALUES (:customerId, NOW(), 0)");
            $stmt->execute([':customerId' => $customerId]);
            $transactionId = $db->lastInsertId();
    
            $totalPrice = 0;
    
            // Insert each item into transaction_items and update product quantities
            foreach ($cart as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $total = $quantity * $price;
    
                $stmt = $db->prepare("INSERT INTO transaction_items (transaction_id, product_name, quantity, price, total) VALUES (:transactionId, :product, :quantity, :price, :total)");
                $stmt->execute([
                    ':transactionId' => $transactionId,
                    ':product' => $product,
                    ':quantity' => $quantity,
                    ':price' => $price,
                    ':total' => $total
                ]);
    
                // Update total price for the transaction
                $totalPrice += $total;
    
                // Update product quantity in the "product" table
                $stmt = $db->prepare("UPDATE product SET quantity = quantity - :quantity WHERE product_name = :product");
                $stmt->execute([':quantity' => $quantity, ':product' => $product]);

                // Insert into sales table
                $stmt = $db->prepare("INSERT INTO sales (customer_id, product_name, quantity, price, total, created_at) VALUES (:customerId, :product, :quantity, :price, :total, NOW())");
                $stmt->execute([
                    ':customerId' => $customerId,
                    ':product' => $product,
                    ':quantity' => $quantity,
                    ':price' => $price,
                    ':total' => $total
                ]);
            }
    
            // Update total price in transactions table
            $stmt = $db->prepare("UPDATE transactions SET total_price = :totalPrice WHERE transaction_id = :transactionId");
            $stmt->execute([':totalPrice' => $totalPrice, ':transactionId' => $transactionId]);
    
            // Commit transaction
            $db->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            // Rollback transaction
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()]);
        }
        exit();
    }
}


?>
