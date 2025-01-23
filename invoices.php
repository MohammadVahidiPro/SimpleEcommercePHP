<?php
include_once __DIR__.'/connection/db.php';
include_once __DIR__.'/utils/util.php';

// Fetch invoice list with customer and purchase details using JOIN
$sql = "SELECT i.id AS invoice_id, i.image_url, DATE(i.invoice_date) AS invoice_date, DATE(i.due_date) AS due_date, i.total_amount, i.status, p.product_name, c.name AS customer_name, c.email AS customer_email
        FROM invoices i
        JOIN purchases p ON i.purchase_id = p.id
        JOIN customers c ON i.customer_id = c.id";
$result = $conn->query($sql);

// Check for data availability
$invoices = ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .container { margin-top: 30px; }
        .add-btn { margin-bottom: 15px; }
        h1 { text-align: center; }
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
    <h1 class="text-center mb-4">Invoice List</h1>
    <a href="add_invoice.php" class="btn btn-primary add-btn">Add New Invoice</a>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>Invoice ID</th>
            <th>Invoice Date</th>
            <th>Due Date</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Product Name</th>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($invoices)): ?>
            <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?php echo htmlspecialchars($invoice['invoice_id']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['invoice_date']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['due_date']); ?></td>
                    <td><?php echo formatMoney($invoice['total_amount']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['status']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['customer_email']); ?></td>
                    <td><img src='uploads/<?php echo htmlspecialchars($invoice['image_url']); ?>' width='150' class='img-thumbnail' alt='Invoice'></td>
                    <td>
                        <a href='edit_invoice.php?id=<?php echo $invoice['invoice_id']; ?>' class='btn btn-sm btn-warning'>Edit</a>
                        <a href='delete_invoice.php?id=<?php echo $invoice['invoice_id']; ?>' class='btn btn-sm btn-danger' onclick='return confirm("Are you sure?")'>Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10" class="text-center">No invoices found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
