<?php
include_once __DIR__.'/connection/db.php';

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Customer ID is missing.</div>";
    exit;
}

$customer_id = $_GET['id'];

// Fetch customer details
$customerQuery = "SELECT * FROM customers WHERE id = ?";
$stmt = $conn->prepare($customerQuery);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Customer not found.</div>";
    exit;
}

$customer = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $updateQuery = "UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssssi", $name, $email, $phone, $address, $customer_id);

    if ($updateStmt->execute()) {
        echo "<div class='alert alert-success'>Customer updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating customer.</div>";
    }
} else {
    $name = $customer['name'];
    $email = $customer['email'];
    $phone = $customer['phone'];
    $address = $customer['address'];
}
?>

    <!DOCTYPE html>
    <html lang="fa">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Customer</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h1 class="mb-4">Edit Customer</h1>
        <form method="POST" class="card p-4 bg-white shadow">
            <div class="mb-3">
                <label for="name" class="form-label">Customer Name</label>
                <input type="text" id="name" name="name" class="form-control" required value="<?php echo htmlspecialchars($name); ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Customer Email</label>
                <input type="email" id="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($email); ?>">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Customer Address</label>
                <textarea id="address" name="address" class="form-control"><?php echo htmlspecialchars($address); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Customer</button>
            <a href="customers.php" class="btn btn-secondary mt-3">Back to Customers List</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>

<?php
$conn->close();
?>
