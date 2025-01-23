<?php
include_once __DIR__.'/connection/db.php';
include_once __DIR__.'/utils/util.php';

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invoice ID is missing.</div>";
    exit;
}

$invoice_id = $_GET['id'];

// Fetch invoice details
$invoiceQuery = "SELECT i.*, p.product_name, p.quantity, p.price, c.name AS customer_name, c.email AS customer_email
                  FROM invoices i
                  JOIN purchases p ON i.purchase_id = p.id
                  JOIN customers c ON i.customer_id = c.id
                  WHERE i.id = ?";
$stmt = $conn->prepare($invoiceQuery);
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Invoice not found.</div>";
    exit;
}

$invoice = $result->fetch_assoc();
$computedTotalAmount = compute_total_amount($invoice['quantity'], $invoice['price'], 10);

// Format dates for HTML date input
$invoice_date = date('Y-m-d', strtotime($invoice['invoice_date']));
$due_date = date('Y-m-d', strtotime($invoice['due_date']));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $invoice_date = $_POST['invoice_date'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];
    $invoice_image = $_FILES['invoice_image']['name'] ?: $invoice['image_url'];

    if (!empty($_FILES['invoice_image']['tmp_name'])) {
        $target_dir = __DIR__ . "/uploads/";
        $target_file = $target_dir . basename($_FILES["invoice_image"]["name"]);
        move_uploaded_file($_FILES["invoice_image"]["tmp_name"], $target_file);
    }

    $updateQuery = "UPDATE invoices SET invoice_date = ?, due_date = ?, status = ?, image_url = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssssi", $invoice_date, $due_date, $status, $invoice_image, $invoice_id);

    if ($updateStmt->execute()) {
        echo "<div class='alert alert-success'>Invoice updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating invoice.</div>";
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Invoice</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script>
            function previewImage() {
                const file = document.getElementById('invoice_image').files[0];
                const reader = new FileReader();
                reader.onloadend = function() {
                    document.getElementById('image_preview').src = reader.result;
                    document.getElementById('image_preview').style.display = 'block';
                }
                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    document.getElementById('image_preview').src = "";
                    document.getElementById('image_preview').style.display = 'none';
                }
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
        <h1 class="mb-4">Edit Invoice</h1>
        <form method="POST" enctype="multipart/form-data" class="card p-4 bg-white shadow">
            <div class="mb-3">
                <label for="invoice_date" class="form-label">Invoice Date</label>
                <input type="date" id="invoice_date" name="invoice_date" class="form-control" required value="<?php echo $invoice_date; ?>">
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" id="due_date" name="due_date" class="form-control" required value="<?php echo $due_date; ?>">
            </div>

            <div class="mb-3">
                <label for="total_amount" class="form-label">Total Amount</label>
                <input type="text" id="total_amount" name="total_amount" class="form-control" value="<?php echo formatMoney($computedTotalAmount); ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="Paid" <?php echo ($invoice['status'] == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                    <option value="Unpaid" <?php echo ($invoice['status'] == 'Unpaid') ? 'selected' : ''; ?>>Unpaid</option>
                    <option value="Processing" <?php echo ($invoice['status'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="invoice_image" class="form-label">Invoice Image</label>
                <input type="file" id="invoice_image" name="invoice_image" class="form-control" onchange="previewImage()">
            </div>

            <img id="image_preview" src="uploads/<?php echo $invoice['image_url']; ?>" alt="Image Preview" class="img-thumbnail mt-3" style="display: block; width: 150px;">

            <button type="submit" class="btn btn-primary">Update Invoice</button>
        </form>
        <a href="invoices.php" class="btn btn-secondary mt-3">Back to Invoices List</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>

<?php
$conn->close();
?>
