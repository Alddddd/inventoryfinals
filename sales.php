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
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <div class="page-container">
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="index1.php"><img src="assets/images/icon/logo.png" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li><a href="index1.php" aria-expanded="true"><i class="ti-dashboard"></i><span>dashboard</span></a></li>
                            <li class="inactive"><a href="table.php" aria-expanded="true"><i class="fa fa-table"></i><span>Item Records</span></a></li>
                            <li class="active"><a href="sales.php" aria-expanded="true"><i class="fa fa-table"></i><span>sales</span></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="header-area">
                <div class="row align-items-center">
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-4 clearfix"></div>
                </div>
            </div>
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Dashboard</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="index1.php">Home</a></li>
                                <li><span>Sales</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 clearfix">
                        <div class="user-profile pull-right">
                            <img class="avatar user-thumb" src="assets/images/author/avatar.png" alt="avatar">
                            <h4 class="user-name dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['username']; ?> <i class="fa fa-angle-down"></i></h4>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="index1.php?logout='1'">Log Out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-content-inner">
                <div class="row">
                    <div class="col-lg-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Check Sales</h4>
                                <form id="date-form">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="startDate">Start Date</label>
                                            <input type="date" class="form-control" id="startDate" name="startDate" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="endDate">End Date</label>
                                            <input type="date" class="form-control" id="endDate" name="endDate" required>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="check-sales">Check Sales</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Sales Report</h4>
                                <div class="table-responsive">
                                    <table class="table text-dark text-center">
                                        <thead class="text-uppercase">
                                            <tr class="table-active">
                                                <th scope="col">Order ID</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Order Price</th>
                                                <th scope="col">Order Date</th>
                                                <th scope="col">Total</th> <!-- Added Total column header -->
                                            </tr>
                                        </thead>
                                        <tbody id="sales-report">
                                            <!-- Sales report will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    <h5>Total Order Price: <span id="total-order-price">₱0.00</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script>
        $(document).ready(function() {
            $('#check-sales').click(function() {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                $.ajax({
                    url: 'server.php',
                    method: 'POST',
                    data: {
                        check_sales: true,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(response) {
                        console.log('Server response:', response);
                        try {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            if (response.success) {
                                var sales = response.sales;
                                var totalOrderPrice = calculateTotalOrderPrice(sales);
                                var salesReport = '';
                                sales.forEach(function(sale) {
                                    var total = sale.quantity * sale.price;
                                    salesReport += '<tr>' +
                                        '<td>' + sale.id + '</td>' +
                                        '<td>' + sale.quantity + '</td>' +
                                        '<td>' + sale.price + '</td>' +
                                        '<td>' + sale.created_at + '</td>' +
                                        '<td>' + total + '</td>' + // Added total column data
                                        '</tr>';
                                });
                                $('#sales-report').html(salesReport);
                                if (typeof totalOrderPrice !== 'undefined') {
                                    $('#total-order-price').text('₱' + totalOrderPrice.toFixed(2));
                                } else {
                                    $('#total-order-price').text('N/A');
                                }
                            } else {
                                console.error('Server error:', response.message);
                                alert('Failed to retrieve sales. Please try again.');
                            }
                        } catch (e) {
                            console.error('Error parsing sales data:', e, response);
                            alert('Failed to parse sales data. Please try again.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error);
                        alert('Failed to retrieve sales. Please try again.');
                    }
                });
            });

            function calculateTotalOrderPrice(sales) {
                var totalOrderPrice = 0;
                sales.forEach(function(sale) {
                    totalOrderPrice += sale.quantity * sale.price; // Adjusted to sum up total prices
                });
                return totalOrderPrice;
            }
        });
    </script>
</body>
</html>
