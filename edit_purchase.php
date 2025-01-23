<?php
include_once __DIR__.'/connection/db.php';
include_once __DIR__.'/utils/util.php';

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Purchase ID is missing.</div>";
    exit;
}

$purchase_id = $_GET['id'];

// Fetch purchase details
$purchaseQuery = "SELECT p.*, c.name AS customer_name, c.email AS customer_email
                   FROM purchases p
                   JOIN customers c ON p.customer_id = c.id
                   WHERE p.id = ?";
$stmt = $conn->prepare($purchaseQuery);
$stmt->bind_param("i", $purchase_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Purchase not found.</div>";
    exit;
}

$purchase = $result->fetch_assoc();
$total_price = compute_total_amount($purchase['quantity'], $purchase['price'], 0);

$purchase_date = date('Y-m-d', strtotime($purchase['purchase_date']));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $purchase_date = $_POST['purchase_date'];

    $updateQuery = "UPDATE purchases SET customer_id = ?, product_name = ?, quantity = ?, price = ?, purchase_date = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("isddsi", $customer_id, $product_name, $quantity, $price, $purchase_date, $purchase_id);

    if ($updateStmt->execute()) {
        echo "<div class='alert alert-success'>Purchase updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating purchase.</div>";
    }
} else {
    $customer_id = $purchase['customer_id'];
    $product_name = $purchase['product_name'];
    $quantity = $purchase['quantity'];
    $price = $purchase['price'];
    $purchase_date = date('Y-m-d', strtotime($purchase['purchase_date']));
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Purchase</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script>
            function updateTotalPrice() {
                const quantity = parseFloat(document.getElementById('quantity').value) || 0;
                const price = parseFloat(document.getElementById('price').value) || 0;
                const totalPrice = quantity * price;
                document.getElementById('total_price').value = totalPrice.toFixed(2);
            }
        </script>
    </head>
    <body class="bg-light">

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
    <div class="container mt-5">
        <h1 class="mb-4">Edit Purchase</h1>
        <form method="POST" class="card p-4 bg-white shadow">
            <div class="mb-3">
                <label for="customer_id" class="form-label">Customer ID</label>
                <input type="number" id="customer_id" name="customer_id" class="form-control" required value="<?php echo htmlspecialchars($customer_id); ?>">
            </div>

            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" id="product_name" name="product_name" class="form-control" required value="<?php echo htmlspecialchars($product_name); ?>">
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="form-control" required value="<?php echo htmlspecialchars($quantity); ?>" oninput="updateTotalPrice()">
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" id="price" name="price" class="form-control" <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required> required value="<?php echo htmlspecialchars($price); ?>" oninput="updateTotalPrice()">
            </div>

            <div class="mb-3">
                <label for="purchase_date" class="form-label">Purchase Date</label>
                <input type="date" id="purchase_date" name="purchase_date" class="form-control" required value="<?php echo htmlspecialchars($purchase_date); ?>">
            </div>

            <div class="mb-3">
                <label for="total_price" class="form-label">Total Price</label>
                <input type="text" id="total_price" name="total_price" class="form-control" value="<?php echo htmlspecialchars($total_price); ?>" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Update Purchase</button>
            <a href="purchases.php" class="btn btn-secondary mt-3">Back to Purchases List</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>

<?php
$conn->close();
?>
