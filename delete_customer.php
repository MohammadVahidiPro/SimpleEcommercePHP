<?php
// delete_customer.php
include_once __DIR__.'/connection/db.php';
include_once __DIR__.'/utils/util.php';

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Customer ID is missing.</div>";
    exit;
}

$customer_id = (int)$_GET['id'];

// Prepare delete query
$deleteQuery = "DELETE FROM customers WHERE id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("i", $customer_id);

if ($stmt->execute()) {
    echo "<div class='alert alert-success'>Customer deleted successfully.</div>";
} else {
    echo "<div class='alert alert-danger'>Error deleting customer: " . $conn->error . "</div>";
}

$conn->close();
header("Location: customers.php");
exit;
?>
