<?php

require_once __DIR__ . '/db.php';

// Create 'customers' table
$sql_customers = "CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),          -- Customer phone number
    address TEXT,               -- Customer address
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql_customers) === TRUE) {
    echo "Table 'customers' created successfully.<br>";
} else {
    echo "Error creating table 'customers': " . $conn->error . "<br>";
}

// Create 'purchases' table
$sql_purchases = "CREATE TABLE IF NOT EXISTS purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Purchase date
    amount DECIMAL(10, 2) NOT NULL,                    -- Purchase amount
    product_name VARCHAR(255) NOT NULL,                -- Product name
    quantity INT NOT NULL,                             -- Quantity purchased
    price DECIMAL(10, 2) NOT NULL,                     -- Unit price
    total_amount DECIMAL(10, 2) GENERATED ALWAYS AS (quantity * price) STORED,  -- Total amount
    payment_method VARCHAR(50),                        -- Payment method
    status VARCHAR(50) DEFAULT 'pending',              -- Purchase status
    FOREIGN KEY (customer_id) REFERENCES customers(id)
)";

if ($conn->query($sql_purchases) === TRUE) {
    echo "Table 'purchases' created successfully.<br>";
} else {
    echo "Error creating table 'purchases': " . $conn->error . "<br>";
}

// Create 'invoices' table
$sql_invoices = "CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT NOT NULL,                          -- Reference to purchase
    customer_id INT NOT NULL,                          -- Reference to customer
    invoice_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Invoice date
    total_amount DECIMAL(10, 2) NOT NULL,              -- Total invoice amount
    due_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,      -- Due date
    image_url VARCHAR(255),                            -- Invoice image URL
    status VARCHAR(50) DEFAULT 'unpaid',               -- Invoice status
    FOREIGN KEY (purchase_id) REFERENCES purchases(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id)
)";

if ($conn->query($sql_invoices) === TRUE) {
    echo "Table 'invoices' created successfully.<br>";
} else {
    echo "Error creating table 'invoices': " . $conn->error . "<br>";
}

// Close connection
$conn->close();



/*
require_once __DIR__.'/db.php';

// ایجاد جدول مشتریان
$sql_customers = "CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),  -- شماره تلفن مشتری
    address TEXT,       -- آدرس مشتری
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql_customers) === TRUE) {
    echo "Table 'customers' created successfully.<br>";
} else {
    echo "Error creating table 'customers': " . $conn->error . "<br>";
}

// ایجاد جدول خریدها
$sql_purchases = "CREATE TABLE IF NOT EXISTS purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- تاریخ خرید
    amount DECIMAL(10, 2) NOT NULL,                      -- مبلغ خرید
    product_name VARCHAR(255) NOT NULL,                   -- نام محصول
    quantity INT NOT NULL,                               -- تعداد محصول خریداری شده
    price DECIMAL(10, 2) NOT NULL,                        -- قیمت واحد محصول
    total_amount DECIMAL(10, 2) GENERATED ALWAYS AS (quantity * price) STORED,  -- مبلغ کل خرید
    payment_method VARCHAR(50),                          -- روش پرداخت
    status VARCHAR(50) DEFAULT 'pending',                -- وضعیت خرید
    FOREIGN KEY (customer_id) REFERENCES customers(id)
)";

if ($conn->query($sql_purchases) === TRUE) {
    echo "Table 'purchases' created successfully.<br>";
} else {
    echo "Error creating table 'purchases': " . $conn->error . "<br>";
}

// ایجاد جدول فاکتورها با خرید id
$sql_invoices = "CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT NOT NULL,                              -- ارجاع به خرید
    customer_id INT NOT NULL,                             -- ارجاع به مشتری
    invoice_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,     -- تاریخ فاکتور
    total_amount DECIMAL(10, 2) NOT NULL,                 -- مبلغ کل فاکتور
    due_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,         -- تاریخ سررسید
    image_url VARCHAR(255),                               -- آدرس تصویر فاکتور
    status VARCHAR(50) DEFAULT 'unpaid',                  -- وضعیت فاکتور
    FOREIGN KEY (purchase_id) REFERENCES purchases(id),  -- ارجاع به جدول خریدها
    FOREIGN KEY (customer_id) REFERENCES customers(id)   -- ارجاع به جدول مشتری‌ها
)";

if ($conn->query($sql_invoices) === TRUE) {
    echo "Table 'invoices' created successfully.<br>";
} else {
    echo "Error creating table 'invoices': " . $conn->error . "<br>";
}

// بستن اتصال
$conn->close();*/
