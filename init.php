<?php

// The path to the SQLite database file
$dbFile = __DIR__ . '/app/database/database.sqlite';

try {
    // Create a new PDO instance
    $pdo = new PDO('sqlite:' . $dbFile);

    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Database connection established successfully.\n";

    // SQL statements to create tables
    $sql = "
        -- Products Table
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            description TEXT,
            sku TEXT UNIQUE,
            purchase_price REAL NOT NULL DEFAULT 0,
            sale_price REAL NOT NULL DEFAULT 0,
            quantity INTEGER NOT NULL DEFAULT 0,
            reorder_level INTEGER NOT NULL DEFAULT 10,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        -- Sales Table
        CREATE TABLE IF NOT EXISTS sales (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            customer_name TEXT,
            total_amount REAL NOT NULL,
            discount REAL DEFAULT 0,
            tax REAL DEFAULT 0,
            final_amount REAL NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        -- Sale Items Table
        CREATE TABLE IF NOT EXISTS sale_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            sale_id INTEGER NOT NULL,
            product_id INTEGER NOT NULL,
            quantity INTEGER NOT NULL,
            price REAL NOT NULL,
            total REAL NOT NULL,
            FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
        );

        -- Purchases Table
        CREATE TABLE IF NOT EXISTS purchases (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            supplier_name TEXT,
            total_amount REAL NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        -- Purchase Items Table
        CREATE TABLE IF NOT EXISTS purchase_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            purchase_id INTEGER NOT NULL,
            product_id INTEGER NOT NULL,
            quantity INTEGER NOT NULL,
            price REAL NOT NULL,
            total REAL NOT NULL,
            FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
        );

        -- Stock Movements Table
        CREATE TABLE IF NOT EXISTS stock_movements (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id INTEGER NOT NULL,
            type TEXT NOT NULL, -- e.g., 'sale', 'purchase', 'return'
            quantity_change INTEGER NOT NULL,
            reference_id INTEGER, -- e.g., sale_id, purchase_id
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        );

        -- Settings Table
        CREATE TABLE IF NOT EXISTS settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            key TEXT NOT NULL UNIQUE,
            value TEXT
        );

        -- Default Settings
        INSERT OR IGNORE INTO settings (key, value) VALUES
        ('company_name', 'My POS System'),
        ('company_address', '123 Main Street'),
        ('company_phone', '555-1234'),
        ('currency_symbol', '$'),
        ('invoice_header', 'Thank you for your business!'),
        ('invoice_footer', 'Please come again.'),
        ('logo_path', NULL),
        ('default_export_format', 'pdf');
    ";

    // Execute the SQL statements
    $pdo->exec($sql);

    echo "Tables created successfully.\n";

} catch (PDOException $e) {
    // Handle connection errors
    die("Database error: " . $e->getMessage());
}
