
<?php

class Dress
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Get dresses from a specific subcategory
    public function getBySubcategory(int $subcategoryId): array
    {
        $sql = "SELECT p.id, p.product_name, p.description,
                       p.price, p.image_url, p.stock_quantity,
                       p.subcategory_id
                FROM products p
                WHERE p.subcategory_id = ?
                ORDER BY p.id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$subcategoryId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
   // Get all images of one dress
public function getImages(int $productId): array
{
    $sql = "SELECT image_id, product_id, image_url, is_primary
            FROM product_images
            WHERE product_id = ?
            ORDER BY is_primary DESC, image_id ASC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$productId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Get one dress by its ID (for the details page later)
    public function getById(int $id): ?array
    {
        $sql = "SELECT p.id, p.product_name, p.description,
                       p.price, p.image_url, p.stock_quantity,
                       p.subcategory_id
                FROM products p
                WHERE p.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $dress = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dress ?: null;
    }
}
