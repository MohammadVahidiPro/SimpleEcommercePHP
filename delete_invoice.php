<?php
// delete_invoice.php
include_once __DIR__.'/connection/db.php';
include_once __DIR__.'/utils/util.php';

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invoice ID is missing.</div>";
    exit;
}

$invoice_id = (int)$_GET['id'];

// Prepare delete query
$deleteQuery = "DELETE FROM invoices WHERE id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("i", $invoice_id);

if ($stmt->execute()) {
    echo "<div class='alert alert-success'>Invoice deleted successfully.</div>";
} else {
    echo "<div class='alert alert-danger'>Error deleting invoice: " . $conn->error . "</div>";
}

$conn->close();
header("Location: invoices.php");
exit;
?>
