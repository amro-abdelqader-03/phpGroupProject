<?php
class Product_variants{
        private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByProduct(int $product_id): array
    {
        $sql = "SELECT id, product_id, size, color, stock_quantity
                FROM product_variants
                WHERE product_id = ?
                ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$product_id]);

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT id, product_id, size, color, stock_quantity
                FROM product_variants
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $variant = $stmt->fetch();

        return $variant ?: null;
    }
}
?>
