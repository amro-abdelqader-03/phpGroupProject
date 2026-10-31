<?php

class CartItem
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByCartID(int $cartId): array
    {
        $sql = "SELECT ci.id, ci.cart_id, ci.product_id, ci.variant_id, ci.quantity,
                       p.image_url AS main_image, pi.image_url AS extra_image
                FROM cart_items AS ci
                INNER JOIN products AS p ON ci.product_id = p.id
                LEFT JOIN product_images AS pi ON p.id = pi.product_id
                WHERE ci.cart_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cartId]);
        return $stmt->fetchAll();
    }

    public function getByID(int $id): ?array
    {
        $sql = "SELECT ci.id, ci.cart_id, ci.product_id, ci.variant_id, ci.quantity,
                       p.image_url AS main_image, pi.image_url AS extra_image
                FROM cart_items AS ci
                INNER JOIN products AS p ON ci.product_id = p.id
                LEFT JOIN product_images AS pi ON p.id = pi.product_id
                WHERE ci.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        return $item ?: null;
    }

    public function addToCart(int $cartId, int $productId, ?int $variantId, int $quantity): int
    {
        $sql = "INSERT INTO cart_items (cart_id, product_id, variant_id, quantity)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cartId, $productId, $variantId, $quantity]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, ?int $variantId, int $quantity): bool
    {
        $sql = "UPDATE cart_items
                SET variant_id = ?, quantity = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$variantId, $quantity, $id]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM cart_items WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
