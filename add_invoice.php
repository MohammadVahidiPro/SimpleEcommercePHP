<?php
include_once __DIR__.'/connection/db.php';
include_once __DIR__.'/utils/util.php';

// Fetch all purchases to populate the dropdown
$purchaseQuery = "SELECT id, product_name, quantity, price, purchase_date FROM purchases";
$purchaseResult = $conn->query($purchaseQuery);

$selectedPurchaseId = isset($_GET['purchase_id']) ? $_GET['purchase_id'] : null;
$computedTotalAmount = 0;

// If purchase_id is provided, pre-select it and compute total amount
if ($selectedPurchaseId) {
    $purchaseDetailsQuery = "SELECT id, product_name, quantity, price, purchase_date FROM purchases WHERE id = '$selectedPurchaseId'";
    $purchaseDetailsResult = $conn->query($purchaseDetailsQuery);

    if ($purchaseDetailsResult->num_rows > 0) {
        $purchase = $purchaseDetailsResult->fetch_assoc();
        $computedTotalAmount = compute_total_amount($purchase['quantity'], $purchase['price'], 10);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $purchase_id = $_POST['purchase_id'];
    $invoice_date = $_POST['invoice_date'];
    $due_date = $_POST['due_date'];
    $invoice_image = $_FILES['invoice_image']['name'];

    // Upload invoice image
    $target_dir = __DIR__ . "/uploads/";
    $target_file = $target_dir . basename($_FILES["invoice_image"]["name"]);
    move_uploaded_file($_FILES["invoice_image"]["tmp_name"], $target_file);

    // Retrieve customer_id based on purchase_id
    $sql_customer = "SELECT customer_id, quantity, price FROM purchases WHERE id = '$purchase_id'";
    $result = $conn->query($sql_customer);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $customer_id = $row['customer_id'];
        $total_amount = compute_total_amount($row['quantity'], $row['price'], 10);
    } else {
        echo "<div class='alert alert-danger'>Purchase not found.</div>";
        exit;
    }

    // Insert invoice into the database
    $sql = "INSERT INTO invoices (purchase_id, customer_id, invoice_date, total_amount, due_date, image_url)
            VALUES ('$purchase_id', '$customer_id', '$invoice_date', '$total_amount', '$due_date', '$invoice_image')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Invoice added successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding invoice: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function updatePurchaseSelection() {
            const purchaseId = document.getElementById('purchase_id').value;
            if (purchaseId) {
                window.location.href = '?purchase_id=' + purchaseId;
            }
        }

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
    <h1 class="mb-4">Add New Invoice</h1>
    <form method="POST" enctype="multipart/form-data" class="card p-4 bg-white shadow">
        <div class="mb-3">
            <label for="purchase_id" class="form-label">Select Purchase</label>
            <select id="purchase_id" name="purchase_id" class="form-select" required onchange="updatePurchaseSelection()">
                <option value="">-- Select a purchase --</option>
                <?php
                while ($purchase = $purchaseResult->fetch_assoc()) {
                    $selected = ($selectedPurchaseId == $purchase['id']) ? 'selected' : '';
                    echo "<option value='{$purchase['id']}' $selected>{$purchase['id']} - {$purchase['product_name']} (Qty: {$purchase['quantity']}, Price: " . formatMoney($purchase['price']) . ", Date: {$purchase['purchase_date']})</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="invoice_date" class="form-label">Invoice Date</label>
            <input type="date" id="invoice_date" name="invoice_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" id="due_date" name="due_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="total_amount" class="form-label">Total Amount</label>
            <input type="text" id="total_amount" name="total_amount" class="form-control" value="<?php echo formatMoney($computedTotalAmount); ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="invoice_image" class="form-label">Invoice Image</label>
            <input type="file" id="invoice_image" name="invoice_image" class="form-control" required onchange="previewImage()">
        </div>

        <img id="image_preview" src="" alt="Image Preview" class="img-thumbnail mt-3" style="display: none; width: 150px; height: 150px; object-fit: cover;">

        <button type="submit" class="btn btn-primary">Add Invoice</button>
    </form>
    <a href="invoices.php" class="btn btn-secondary mt-3">Back to Invoices List</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('invoice_image').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            document.getElementById('image_preview').style.display = 'block';
        } else {
            document.getElementById('image_preview').style.display = 'none';
        }
    });
</script>
</body>
</html>
