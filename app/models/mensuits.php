<?php

class Mensuits {

private PDO $db;

function __construct(PDO $db) {
    $this->db = $db;
}

public function getAllSuits() {

    $sql = "SELECT
                products.id AS product_id,
                products.product_name,
                products.description AS product_description,
                products.price,
                products.image_url,
                products.stock_quantity,
                subcategories.name AS subcategory_name
            FROM categories
            INNER JOIN subcategories
                ON categories.id = subcategories.category_id
            INNER JOIN products
                ON subcategories.id = products.subcategory_id
            WHERE categories.name = :category_name
            AND categories.status = 'active'";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':category_name' => "Men's Suits"
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getSuitById($id)
{
    $sql = "SELECT
                products.id AS product_id,
                products.product_name,
                products.description AS product_description,
                products.price,
                products.image_url,
                products.stock_quantity,
                subcategories.name AS subcategory_name
            FROM categories
            INNER JOIN subcategories
                ON categories.id = subcategories.category_id
            INNER JOIN products
                ON subcategories.id = products.subcategory_id
            WHERE categories.name = :category_name
            AND categories.status = 'active'
            AND products.id = :product_id";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':category_name' => "Men's Suits",
        ':product_id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getSuitImages($productId)
{
    $sql = "SELECT
                image_id,
                product_id,
                image_url,
                is_primary
            FROM product_images
            WHERE product_id = :product_id
            ORDER BY is_primary DESC";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':product_id' => $productId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getSuitVariants($productId)
{
    $sql = "SELECT
                id AS variant_id,
                product_id,
                size,
                color,
                stock_quantity
            FROM product_variants
            WHERE product_id = :product_id
            ORDER BY size ASC";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':product_id' => $productId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}


?>