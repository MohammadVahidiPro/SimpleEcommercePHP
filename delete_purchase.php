<?php
// delete_purchase.php
include_once __DIR__.'/connection/db.php';
include_once __DIR__.'/utils/util.php';

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Purchase ID is missing.</div>";
    exit;
}

$purchase_id = (int)$_GET['id'];

// Prepare delete query
$deleteQuery = "DELETE FROM purchases WHERE id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("i", $purchase_id);

if ($stmt->execute()) {
    echo "<div class='alert alert-success'>Purchase deleted successfully.</div>";
} else {
    echo "<div class='alert alert-danger'>Error deleting purchase: " . $conn->error . "</div>";
}

$conn->close();
header("Location: purchases.php");
exit;
?>
