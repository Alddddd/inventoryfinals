<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.


if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
    exit();
}
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Inventory Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/metisMenu.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <link rel="stylesheet" href="assets/css/typography.css">
    <link rel="stylesheet" href="assets/css/default-css.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>

    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="index2.php"><img src="assets/images/icon/logo.png" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li><a href="index2.php" aria-expanded="true"><i class="ti-dashboard"></i><span>dashboard</span></a></li>
                            <li class="active"><a href="transaction.php" aria-expanded="true"><i class="fa fa-table"></i><span>transaction</span></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->
        
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <!-- nav and search button -->
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    
                    <!-- profile info & task notification-->
                    <div class="col-md-6 col-sm-4 clearfix"></div>
                </div>
            </div>
            <!-- header area end -->

            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Dashboard</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="index2.php">Home</a></li>
                                <li><span>Transaction</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 clearfix">
                        <div class="user-profile pull-right">
                            <img class="avatar user-thumb" src="assets/images/author/avatar.png" alt="avatar">
                            <h4 class="user-name dropdown-toggle" data-toggle="dropdown"><?php echo htmlspecialchars($_SESSION['username']); ?> <i class="fa fa-angle-down"></i></h4>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="index2.php?logout='1'">Log Out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- page title area end -->

            <div class="main-content-inner">
                <!-- Sales Form -->
                <div class="row">
                    <div class="col-lg-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">New Sale</h4>
                                <form id="sales-form">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="customerName">Customer Name</label>
                                            <input type="text" class="form-control" id="customerName" name="customerName" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="customerEmail">Customer Email</label>
                                            <input type="email" class="form-control" id="customerEmail" name="customerEmail" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="product">Product</label>
                                            <select class="form-control" id="product" name="product">
                                                <?php 
                                                    $dsn = 'mysql:host=localhost;dbname=inventorymanagement';
                                                    $username = 'root';
                                                    $password = '';
                                                    $options = [
                                                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                                                    ];

                                                    try {
                                                        $pdo = new PDO($dsn, $username, $password, $options);
                                                        $stmt = $pdo->query("SELECT * FROM product");
                                                        while ($row = $stmt->fetch()) {
                                                            echo "<option value='{$row['product_id']}' data-price='{$row['price']}'>{$row['product_name']}</option>";
                                                        }
                                                    } catch (PDOException $e) {
                                                        echo 'Connection failed: ' . $e->getMessage();
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="quantity">Quantity</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="price">Price</label>
                                            <input type="text" class="form-control" id="price" name="price" readonly>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="add-to-cart">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Table -->
                <div class="row">
                    <div class="col-lg-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Cart</h4>
                                <div class="table-responsive">
                                    <table class="table text-dark text-center">
                                        <thead class="text-uppercase">
                                            <tr class="table-active">
                                                <th scope="col">Product</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cart-body">
                                            <!-- Cart items will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-right">
                                    <h4>Total: ₱<span id="cart-total">0.00</span></h4>
                                    <button type="button" class="btn btn-success" id="checkout">Checkout</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- main content area end -->
    </div>
    <!-- page container area end -->

    <!-- jquery latest version -->
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <!-- others plugins -->
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script>
$(document).ready(function() {
    // Update price when product is selected
    $('#product').change(function() {
        var price = $(this).find(':selected').data('price');
        $('#price').val(price);
    }).change(); // Trigger change event on page load to set initial price

    $('#add-to-cart').click(function() {
        var productId = $('#product').val();
        var productName = $('#product option:selected').text();
        var quantity = $('#quantity').val();
        var price = $('#price').val();

        // Check available quantity
        $.ajax({
            url: 'server.php',
            method: 'POST',
            data: {
                action: 'checkQuantity',
                productId: productId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.quantity >= quantity) {
                        var total = quantity * price;
                        var cartRow = '<tr>' +
                            '<td>' + productName + '</td>' +
                            '<td>' + quantity + '</td>' +
                            '<td>' + price + '</td>' +
                            '<td>' + total + '</td>' +
                            '<td><button class="btn btn-danger btn-sm remove-item">Remove</button></td>' +
                            '</tr>';
                        $('#cart-body').append(cartRow);
                        updateCartTotal();
                    } else {
                        alert('Insufficient stock for ' + productName);
                    }
                } else {
                    alert('Failed to check stock: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Failed to check stock. Please try again.');
            }
        });
    });

    // Remove item from cart
    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        updateCartTotal();
    });

    // Checkout
    $('#checkout').click(function() {
        var cart = [];
        $('#cart-body tr').each(function() {
            var product = $(this).find('td:nth-child(1)').text();
            var quantity = $(this).find('td:nth-child(2)').text();
            var price = $(this).find('td:nth-child(3)').text();
            var total = $(this).find('td:nth-child(4)').text();
            cart.push({ product: product, quantity: quantity, price: price, total: total });
        });

        if (cart.length === 0) {
            alert('Cart is empty. Please add items to the cart before checking out.');
            return; // Prevent checkout if cart is empty
        }

        var customerName = $('#customerName').val();
        var customerEmail = $('#customerEmail').val();

        $.ajax({
            url: 'server.php',
            method: 'POST',
            data: {
                checkout: true,
                customerName: customerName,
                customerEmail: customerEmail,
                cart: JSON.stringify(cart)
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Checkout successful!');
                    location.reload();
                } else {
                    alert('Checkout failed: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Checkout failed. Please try again.');
            }
        });
    });

    function updateCartTotal() {
        var total = 0;
        $('#cart-body tr').each(function() {
            total += parseFloat($(this).find('td:nth-child(4)').text());
        });
        $('#cart-total').text(total.toFixed(2));
    }
});


</script>


    
</body>

</html>