<?php

class Product extends Model {

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO products (name, description, sku, purchase_price, sale_price, quantity, reorder_level)
             VALUES (:name, :description, :sku, :purchase_price, :sale_price, :quantity, :reorder_level)"
        );
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $data['id'] = $id;
        $stmt = $this->db->prepare(
            "UPDATE products
             SET name = :name, description = :description, sku = :sku,
                 purchase_price = :purchase_price, sale_price = :sale_price,
                 quantity = :quantity, reorder_level = :reorder_level,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id"
        );
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function isNameUnique($name, $id = 0) {
        $sql = "SELECT id FROM products WHERE name = :name";
        if ($id > 0) {
            $sql .= " AND id != :id";
        }
        $stmt = $this->db->prepare($sql);
        $params = ['name' => $name];
        if ($id > 0) {
            $params['id'] = $id;
        }
        $stmt->execute($params);
        return $stmt->fetch() === false;
    }

    public function isInUse($id) {
        // Check in sale_items
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM sale_items WHERE product_id = :id");
        $stmt->execute(['id' => $id]);
        if ($stmt->fetch()['count'] > 0) {
            return true;
        }

        // Check in purchase_items
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM purchase_items WHERE product_id = :id");
        $stmt->execute(['id' => $id]);
        if ($stmt->fetch()['count'] > 0) {
            return true;
        }

        return false;
    }

    public function updateStock($productId, $quantityChange) {
        $sql = "UPDATE products SET quantity = quantity + :quantityChange, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'quantityChange' => $quantityChange,
            'id' => $productId
        ]);
    }

    public function search($term) {
        $sql = "SELECT id, name, sku, sale_price FROM products WHERE name LIKE :term OR sku LIKE :term LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['term' => '%' . $term . '%']);
        return $stmt->fetchAll();
    }
}
