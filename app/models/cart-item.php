<?php

class CartItem
{
    private const SIZE_REQUIRED_CATEGORIES = [1, 3, 4, 5, 7];

    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByCartID(int $cartId): array
    {
        $sql = "SELECT ci.id, ci.cart_id, ci.product_id, ci.variant_id, ci.quantity,
                   p.product_name, p.price,
                   COALESCE(
                       NULLIF(p.image_url, ''),
                       (SELECT pi.image_url
                        FROM product_images AS pi
                        WHERE pi.product_id = p.id
                        ORDER BY pi.is_primary DESC, pi.image_id ASC
                        LIMIT 1)
                   ) AS main_image,
                   pv.size AS size, pv.color AS color,
                   CASE WHEN s.category_id IN (1, 3, 4, 5, 7) THEN 1 ELSE 0 END AS requires_size
                FROM cart_items AS ci
                INNER JOIN products AS p ON ci.product_id = p.id
                LEFT JOIN subcategories AS s ON s.id = p.subcategory_id
            LEFT JOIN product_variants AS pv ON pv.id = ci.variant_id AND pv.product_id = p.id
            WHERE ci.cart_id = ?
            ORDER BY ci.id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cartId]);
        return $stmt->fetchAll();
    }

    public function getByID(int $id): ?array
    {
        $sql = "SELECT ci.id, ci.cart_id, ci.product_id, ci.variant_id, ci.quantity,
                   p.product_name, p.price,
                   COALESCE(
                       NULLIF(p.image_url, ''),
                       (SELECT pi.image_url
                        FROM product_images AS pi
                        WHERE pi.product_id = p.id
                        ORDER BY pi.is_primary DESC, pi.image_id ASC
                        LIMIT 1)
                   ) AS main_image,
                   pv.size AS size, pv.color AS color,
                   CASE WHEN s.category_id IN (1, 3, 4, 5, 7) THEN 1 ELSE 0 END AS requires_size
                FROM cart_items AS ci
                INNER JOIN products AS p ON ci.product_id = p.id
                LEFT JOIN subcategories AS s ON s.id = p.subcategory_id
            LEFT JOIN product_variants AS pv ON pv.id = ci.variant_id AND pv.product_id = p.id
                WHERE ci.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        return $item ?: null;
    }

    public function addToCart(int $cartId, int $productId, ?int $variantId, int $quantity): int
    {
        $stockStmt = $this->db->prepare(
            'SELECT p.stock_quantity, pv.stock_quantity AS variant_stock, pv.size AS variant_size, s.category_id
             FROM products p
             JOIN subcategories s ON s.id = p.subcategory_id
             LEFT JOIN product_variants pv ON pv.id = ? AND pv.product_id = p.id
             WHERE p.id = ?'
        );
        $stockStmt->execute([$variantId, $productId]);
        $stock = $stockStmt->fetch();
        if (!$stock) {
            throw new DomainException('This product is no longer available.');
        }
        if (in_array((int) $stock['category_id'], self::SIZE_REQUIRED_CATEGORIES, true) && empty($stock['variant_size'])) {
            throw new DomainException('Choose an available size before adding this item to your bag.');
        }
        $available = $variantId === null ? $stock['stock_quantity'] : $stock['variant_stock'];
        if ($available === null) {
            throw new DomainException('The selected product option is unavailable.');
        }

        $existingStmt = $this->db->prepare(
            'SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ? AND variant_id <=> ? FOR UPDATE'
        );
        $existingStmt->execute([$cartId, $productId, $variantId]);
        $existing = $existingStmt->fetch();
        if ($existing) {
            $nextQuantity = (int) $existing['quantity'] + $quantity;
            if ($nextQuantity > min(99, (int) $available)) {
                throw new DomainException('The requested quantity exceeds available stock.');
            }
            $updateStmt = $this->db->prepare('UPDATE cart_items SET quantity = ? WHERE id = ?');
            $updateStmt->execute([$nextQuantity, $existing['id']]);
            return (int) $existing['id'];
        }
        if ($quantity > min(99, (int) $available)) {
            throw new DomainException('The requested quantity exceeds available stock.');
        }

        $sql = "INSERT INTO cart_items (cart_id, product_id, variant_id, quantity)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cartId, $productId, $variantId, $quantity]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, ?int $variantId, int $quantity): bool
    {
        $stockStmt = $this->db->prepare(
            'SELECT ci.product_id, p.stock_quantity, pv.stock_quantity AS variant_stock,
                    pv.size AS variant_size, s.category_id
             FROM cart_items ci
             JOIN products p ON p.id = ci.product_id
             JOIN subcategories s ON s.id = p.subcategory_id
             LEFT JOIN product_variants pv ON pv.id = ? AND pv.product_id = p.id
             WHERE ci.id = ?'
        );
        $stockStmt->execute([$variantId, $id]);
        $stock = $stockStmt->fetch();
        if (!$stock) {
            return false;
        }
        if (in_array((int) $stock['category_id'], self::SIZE_REQUIRED_CATEGORIES, true) && empty($stock['variant_size'])) {
            throw new DomainException('Choose an available size before updating this item in your bag.');
        }
        $available = $variantId === null ? $stock['stock_quantity'] : $stock['variant_stock'];
        if ($available === null || $quantity > min(99, (int) $available)) {
            throw new DomainException('The requested quantity exceeds available stock or uses an invalid option.');
        }

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
