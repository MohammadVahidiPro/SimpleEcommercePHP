<?php
// شامل فایل اتصال به پایگاه داده
include_once __DIR__ . '/connection/db.php';

// دریافت شناسه فاکتور از URL
$invoice_id = $_GET['id'];

// دریافت اطلاعات فاکتور به همراه جزئیات خرید و مشتری
$sql = "SELECT i.id AS invoice_id, 
               i.invoice_number, 
               i.invoice_date, 
               i.invoice_image, 
               p.product_name, 
               p.quantity, 
               p.price, 
               c.name AS customer_name, 
               c.email AS customer_email
        FROM invoices i
        JOIN purchases p ON i.purchase_id = p.id
        JOIN customers c ON p.customer_id = c.id
        WHERE i.id = $invoice_id";

$result = $conn->query($sql);
$invoice = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جزئیات فاکتور</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>جزئیات فاکتور</h1>
    <table>
        <tr>
            <th>شناسه فاکتور</th>
            <td><?php echo $invoice['invoice_id']; ?></td>
        </tr>
        <tr>
            <th>شماره فاکتور</th>
            <td><?php echo $invoice['invoice_number']; ?></td>
        </tr>
        <tr>
            <th>تاریخ فاکتور</th>
            <td><?php echo $invoice['invoice_date']; ?></td>
        </tr>
        <tr>
            <th>تصویر فاکتور</th>
            <td><img src="uploads/<?php echo $invoice['invoice_image']; ?>" alt="Invoice Image" width="200"></td>
        </tr>
        <tr>
            <th>نام محصول</th>
            <td><?php echo $invoice['product_name']; ?></td>
        </tr>
        <tr>
            <th>تعداد</th>
            <td><?php echo $invoice['quantity']; ?></td>
        </tr>
        <tr>
            <th>قیمت</th>
            <td><?php echo $invoice['price']; ?></td>
        </tr>
        <tr>
            <th>نام خریدار</th>
            <td><?php echo $invoice['customer_name']; ?></td>
        </tr>
        <tr>
            <th>ایمیل خریدار</th>
            <td><?php echo $invoice['customer_email']; ?></td>
        </tr>
    </table>
    <a href="list_invoices.php">بازگشت به لیست فاکتورها</a>
</div>
</body>
</html>

<?php
$conn->close();
?>
