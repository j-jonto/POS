<?php

class Purchase extends Model {

    public function create($data) {
        // Manually load models needed for the transaction
        require_once ROOT_PATH . '/app/models/Product.php';
        require_once ROOT_PATH . '/app/models/StockMovement.php';

        $productModel = new Product();
        $stockMovementModel = new StockMovement();

        $this->db->beginTransaction();

        try {
            // 1. Insert into purchases table
            $stmt = $this->db->prepare("INSERT INTO purchases (supplier_name, total_amount) VALUES (:supplier_name, :total_amount)");
            $stmt->execute([
                'supplier_name' => $data['supplier_name'],
                'total_amount' => $data['total_amount']
            ]);
            $purchaseId = $this->db->lastInsertId();

            // 2. Insert into purchase_items and update product stock
            foreach ($data['items'] as $item) {
                // Insert into purchase_items
                $itemStmt = $this->db->prepare(
                    "INSERT INTO purchase_items (purchase_id, product_id, quantity, price, total)
                     VALUES (:purchase_id, :product_id, :quantity, :price, :total)"
                );
                $itemStmt->execute([
                    'purchase_id' => $purchaseId,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price']
                ]);

                // 3. Update product stock
                $productModel->updateStock($item['id'], $item['quantity']);

                // 4. Log stock movement
                $stockMovementModel->log([
                    'product_id' => $item['id'],
                    'type' => 'purchase',
                    'quantity_change' => $item['quantity'],
                    'reference_id' => $purchaseId
                ]);
            }

            $this->db->commit();
            return $purchaseId;

        } catch (Exception $e) {
            $this->db->rollBack();
            // In a real app, you would log the error
            // error_log($e->getMessage());
            return false;
        }
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM purchases ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM purchases WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $purchase = $stmt->fetch();

        if ($purchase) {
            $itemStmt = $this->db->prepare(
                "SELECT pi.*, p.name as product_name
                 FROM purchase_items pi
                 JOIN products p ON pi.product_id = p.id
                 WHERE pi.purchase_id = :id"
            );
            $itemStmt->execute(['id' => $id]);
            $purchase['items'] = $itemStmt->fetchAll();
        }

        return $purchase;
    }
}
