<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.


if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('Location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('Location: login.php');
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
    <link rel="stylesheet" href="assets/css/typography.css">
    <link rel="stylesheet" href="assets/css/default-css.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <div id="preloader">
        <div class="loader"></div>
    </div>

    <div class="page-container">
        <!-- Sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="index1.php"><img src="logooo.jpg" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li>
                                <a href="index1.php" aria-expanded="true"><i class="ti-dashboard"></i><span>Dashboard</span></a>
                            </li>
                            <li class="active">
                                <a href="table.php" aria-expanded="true"><i class="fa fa-table"></i><span>Item Records</span></a>
                            </li>
                            <li>
                                <a href="sales.php" aria-expanded="true"><i class="fa fa-table"></i><span>Sales</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Sidebar menu area end -->

        <!-- Main content area start -->
        <div class="main-content">
            <!-- Header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <!-- Nav and search button -->
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <!-- Profile info & task notification -->
                    <div class="col-md-6 col-sm-4 clearfix">
                        <div class="user-profile pull-right">
                            <img class="avatar user-thumb" src="assets/images/author/avatar.png" alt="avatar">
                            <h4 class="user-name dropdown-toggle" data-toggle="dropdown"><?php echo htmlspecialchars($_SESSION['username']); ?> <i class="fa fa-angle-down"></i></h4>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="index1.php?logout=1">Log Out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header area end -->

            <!-- Page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Dashboard</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="index1.php">Home</a></li>
                                <li><span>Item Records</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 clearfix"></div>
                </div>
            </div>
            <!-- Page title area end -->

            <!-- Main content area start -->
            <div class="main-content-inner">
                <div class="row">
                    <div class="col-lg-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Add Item Here</h4>
                                <form method="POST" action="additem.php" class="form-inline">
                                    <div class="form-group">
                                        <label for="product_name">Product Name</label>
                                        <input type="text" class="form-control" name="product_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" class="form-control" name="price" step="0.01" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" class="form-control" name="quant" min="1" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="add">Add item</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products table start -->
                    <div class="col-lg-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Products</h4>
                                <div class="single-table">
                                    <div class="table-responsive">
                                        <table class="table text-dark text-center">
                                            <thead class="text-uppercase">
                                                <tr class="table-active">
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Price</th>
                                                    <th scope="col">Quantity</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                try {
                                                    $pdo = new PDO('mysql:host=localhost;dbname=inventorymanagement', 'root', '');
                                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                                    $stmt = $pdo->query("SELECT * FROM product");
                                                    $count = 0;

                                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                        $count++;
                                                        echo "<tr>
                                                            <td>{$count}</td>
                                                            <td>{$row['product_name']}</td>
                                                            <td>{$row['price']}</td>
                                                            <td>{$row['quantity']}</td>
                                                            <td>
                                                                <a href='edit.php?id={$row['product_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                                                <a href='delete.php?id={$row['product_id']}' class='btn btn-danger btn-sm'>Delete</a>
                                                            </td>
                                                        </tr>";
                                                    }
                                                } catch (PDOException $e) {
                                                    echo "Error: " . $e->getMessage();
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Products table end -->
                </div>
            </div>
            <!-- Main content area end -->
        </div>
        <!-- Main content area end -->
    </div>
    <!-- Page container area end -->

    <!-- jQuery and other plugins -->
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>
</html>
