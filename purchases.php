<?php
// Include database connection file
include_once __DIR__.'/connection/db.php';
include_once __DIR__.'/utils/util.php';

// Fetch purchase list with customer details using JOIN
$sql = "SELECT p.id AS purchase_id, p.product_name, p.quantity, p.price, DATE(p.purchase_date) AS purchase_date, c.name AS customer_name, c.email AS customer_email, i.id AS invoice_id
        FROM purchases p 
        JOIN customers c ON p.customer_id = c.id
        LEFT JOIN invoices i ON p.id = i.purchase_id";
$result = $conn->query($sql);
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Purchase List</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <style>
            .container { margin-top: 30px; }
            .add-btn { margin-bottom: 15px; }
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
        <h1 class="text-center mb-4">Purchase List</h1>
        <a href="add_purchase.php" class="btn btn-primary add-btn">Add New Purchase</a>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
            <tr>
                <th>Purchase ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Amount</th>
                <th>Purchase Date</th>
                <th>Customer Name</th>
                <th>Customer Email</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $totalAmount = compute_total_amount($row['quantity'], $row['price']);
                    echo "<tr>";
                    echo "<td>" . $row['purchase_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                    echo "<td>" . formatMoney($row['price']) . "</td>";
                    echo "<td>" . formatMoney($totalAmount) . "</td>";
                    echo "<td>" . htmlspecialchars($row['purchase_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['customer_email']) . "</td>";
                    echo "<td>";
                    echo "<a href='edit_purchase.php?id=" . $row['purchase_id'] . "' class='btn btn-warning btn-sm'>Edit</a> ";
                    echo "<a href='delete_purchase.php?id=" . $row['purchase_id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a> ";
                    if ($row['invoice_id']) {
                        echo "<a href='edit_invoice.php?id=" . $row['invoice_id'] . "' class='btn btn-info btn-sm'>Edit Invoice</a>";
                    } else {
                        echo "<a href='add_invoice.php?purchase_id=" . $row['purchase_id'] . "' class='btn btn-success btn-sm'>Add Invoice</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9' class='text-center'>No purchases found.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>

<?php
$conn->close();
?>
