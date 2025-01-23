<?php
require_once __DIR__."/connection/db.php";
require_once __DIR__."/utils/util.php";


// Get customer count
$sql_customers_count = "SELECT COUNT(*) AS customer_count FROM customers";
$result_customers_count = $conn->query($sql_customers_count);
$customers_count = $result_customers_count->fetch_assoc()['customer_count'];

// Get purchase count
$sql_purchases_count = "SELECT COUNT(*) AS purchase_count FROM purchases";
$result_purchases_count = $conn->query($sql_purchases_count);
$purchases_count = $result_purchases_count->fetch_assoc()['purchase_count'];

// Get invoice count
$sql_invoices_count = "SELECT COUNT(*) AS invoice_count FROM invoices";
$result_invoices_count = $conn->query($sql_invoices_count);
$invoices_count = $result_invoices_count->fetch_assoc()['invoice_count'];

// Get total invoice amount
$sql_total_amount = "SELECT SUM(total_amount) AS total_amount FROM invoices";
$result_total_amount = $conn->query($sql_total_amount);
$total_amount = $result_total_amount->fetch_assoc()['total_amount'];

// Calculate 10% profit
$profit = $total_amount * 0.10;

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .stat-card {
            background: #007bff;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .navbar {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="customers.php">Customers</a></li>
                <li class="nav-item"><a class="nav-link" href="purchases.php">Purchases</a></li>
                <li class="nav-item"><a class="nav-link" href="invoices.php">Invoices</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="text-center mb-4">Dashboard Overview</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="stat-card">
                <h4>Customers</h4>
                <p class="fs-2"> <?php echo number_format($customers_count); ?> </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-success">
                <h4>Purchases</h4>
                <p class="fs-2"> <?php echo number_format($purchases_count); ?> </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-warning">
                <h4>Invoices</h4>
                <p class="fs-2"> <?php echo number_format($invoices_count); ?> </p>
            </div>
        </div>
    </div>
    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="stat-card bg-danger">
                <h4>Total Invoice Amount</h4>
                <p class="fs-2"> <?php echo formatMoney($total_amount); ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card bg-info">
                <h4>Profit (10%)</h4>
                <p class="fs-2"> <?php echo formatMoney($profit); ?></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
