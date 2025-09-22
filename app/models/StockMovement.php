<?php

class StockMovement extends Model {

    /**
     * Logs a change in stock for a product.
     *
     * @param array $data An array containing product_id, type, quantity_change, and reference_id.
     * @return bool True on success, false on failure.
     */
    public function log($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO stock_movements (product_id, type, quantity_change, reference_id)
             VALUES (:product_id, :type, :quantity_change, :reference_id)"
        );

        return $stmt->execute([
            'product_id' => $data['product_id'],
            'type' => $data['type'],
            'quantity_change' => $data['quantity_change'],
            'reference_id' => $data['reference_id'] ?? null
        ]);
    }

}
