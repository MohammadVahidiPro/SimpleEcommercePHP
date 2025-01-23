<?php
include_once __DIR__.'/connection/db.php';
include_once __DIR__.'/utils/util.php';

// Fetch customers for dropdown
$customers_query = "SELECT id, name FROM customers";
$customers_result = $conn->query($customers_query);

$selected_customer_id = isset($_GET['customer_id']) ? (int)$_GET['customer_id'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = trim($_POST['customer_id']);
    $product_name = trim($_POST['product_name']);
    $quantity = trim($_POST['quantity']);
    $price = trim($_POST['price']);
    $purchase_date = trim($_POST['purchase_date']);
    $amount = compute_total_amount($quantity, $price);

    if (empty($customer_id) || empty($product_name) || empty($quantity) || empty($price)) {
        echo "<div class='alert alert-danger'>All fields are required!</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO purchases (customer_id, product_name, quantity, amount, price, purchase_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isidds", $customer_id, $product_name, $quantity, $amount, $price, $purchase_date);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Purchase added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Purchase</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
<div class="container mt-5">
    <h1 class="mb-4">Add New Purchase</h1>
    <form action="add_purchase.php" method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label for="customer_id" class="form-label">Select Customer:</label>
            <select id="customer_id" name="customer_id" class="form-select" required>
                <option value="">-- Choose a Customer --</option>
                <?php while ($row = $customers_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $selected_customer_id) ? 'selected' : ''; ?>>
                        <?php echo $row['id'] . ". " . $row['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="product_name" class="form-label">Product Name:</label>
            <input type="text" id="product_name" name="product_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price:</label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>        </div>
        <div class="mb-3">
            <label for="purchase_date" class="form-label">Purchase Date:</label>
            <input type="date" id="purchase_date" name="purchase_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Purchase</button>
    </form>
    <a href="purchases.php" class="btn btn-secondary mt-3">Back to Purchases List</a>
</div>
</body>
</html>
