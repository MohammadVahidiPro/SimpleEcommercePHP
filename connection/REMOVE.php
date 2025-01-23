<?php

require_once __DIR__.'/db.php';

// دراپ کردن جدول فاکتورها
$sql_invoices = "DROP TABLE IF EXISTS invoices";

if ($conn->query($sql_invoices) === TRUE) {
    echo "Table 'invoices' dropped successfully.<br>";
} else {
    echo "Error dropping table 'invoices': " . $conn->error . "<br>";
}

// دراپ کردن جدول خریدها
$sql_purchases = "DROP TABLE IF EXISTS purchases";

if ($conn->query($sql_purchases) === TRUE) {
    echo "Table 'purchases' dropped successfully.<br>";
} else {
    echo "Error dropping table 'purchases': " . $conn->error . "<br>";
}

// دراپ کردن جدول مشتریان
$sql_customers = "DROP TABLE IF EXISTS customers";

if ($conn->query($sql_customers) === TRUE) {
    echo "Table 'customers' dropped successfully.<br>";
} else {
    echo "Error dropping table 'customers': " . $conn->error . "<br>";
}

// بستن اتصال
$conn->close();

