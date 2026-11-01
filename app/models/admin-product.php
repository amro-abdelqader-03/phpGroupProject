<?php
class Admin_product
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $sql = "SELECT id, subcategory_id, product_name, price, cost_price, description, image_url
                FROM products
                ORDER BY id DESC";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getByID(int $id): ?array
    {
        // Get product details
        $sql = "SELECT id AS product_id, product_name, price, cost_price,
                   description, stock_quantity
            FROM products
            WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            return null;
        }

        // Get product images
        $sql = "SELECT
                image_id,
                image_url,
                is_primary
            FROM product_images
            WHERE product_id = ?
            ORDER BY is_primary DESC, image_id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $product['images'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get product variants
        $sql = "SELECT
                id AS variant_id,
                size,
                color,
                stock_quantity
            FROM product_variants
            WHERE product_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $product['variants'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $product;
    }

    public function create(
        string $name,
        string $description,
        int $subcategory,
        float $price,
        float $costPrice,
        array $images,
    ): int {
        $primaryImage = $images[0]['url'] ?? '';

        $stmt = $this->db->prepare("INSERT INTO products (product_name, description, subcategory_id, price, cost_price, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $subcategory, $price, $costPrice, $primaryImage]);
        $id = (int) $this->db->lastInsertId();
        $imageStmt = $this->db->prepare("INSERT INTO product_images (product_id, image_url, is_primary) VALUES (?, ?, ?)");
        foreach ($images as $index => $image) {
            $imageStmt->execute([$id, $image['url'], $index === 0 ? 1 : 0]);
        }
        $this->db->commit();
        return $id;

    }

    public function update(
        int $id,
        string $name,
        string $description,
        int $subcategory_id,
        float $cost_price,
        float $price

    ): bool {

        $sql = "UPDATE products
                    SET product_name = ?,
                        description = ?,
                        subcategory_id = ?,
                        cost_price = ?,
                        price = ?
                    WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $name,
            $description,
            $subcategory_id,
            $cost_price,
            $price,
            $id
        ]);


        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM products WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }
}
?>