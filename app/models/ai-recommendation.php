<?php

class Ai_recommendation
{
    private const PRODUCT_CATEGORIES = [
        'suit' => "Men's Suits",
        'dress' => 'Dresses',
    ];

    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Return in-stock products of the requested type that fit the budget.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getMatchingProducts(string $productType, float $budget, int $limit = 20): array
    {
        $categoryName = self::PRODUCT_CATEGORIES[$productType] ?? null;

        if ($categoryName === null) {
            return [];
        }

        $limit = max(1, min($limit, 50));

        $sql = "SELECT
                    p.id AS product_id,
                    p.product_name,
                    p.description,
                    p.price,
                    p.image_url,
                    p.stock_quantity,
                    c.name AS category_name,
                    s.name AS subcategory_name
                FROM products p
                INNER JOIN subcategories s ON s.id = p.subcategory_id
                INNER JOIN categories c ON c.id = s.category_id
                WHERE c.name = :category_name
                AND c.status = 'active'
                AND p.price <= :budget
                AND p.stock_quantity > 0
                ORDER BY p.price ASC, p.id ASC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':category_name', $categoryName, PDO::PARAM_STR);
        $stmt->bindValue(':budget', number_format($budget, 2, '.', ''), PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($products === []) {
            return [];
        }

        $productIds = array_map(
            static fn(array $product): int => (int) $product['product_id'],
            $products
        );

        $primaryImages = $this->getPrimaryImages($productIds);
        $variants = $this->getAvailableVariants($productIds);

        foreach ($products as &$product) {
            $productId = (int) $product['product_id'];

            $product['product_id'] = $productId;
            $product['price'] = (float) $product['price'];
            $product['stock_quantity'] = (int) $product['stock_quantity'];
            $product['image_url'] = $primaryImages[$productId]
                ?? ($product['image_url'] ?: null);

            $productVariants = $variants[$productId] ?? [];
            $sizes = [];
            $colors = [];

            foreach ($productVariants as $variant) {
                if ($variant['size'] !== null && $variant['size'] !== '') {
                    $sizes[] = (string) $variant['size'];
                }

                if ($variant['color'] !== null && $variant['color'] !== '') {
                    $colors[] = (string) $variant['color'];
                }
            }

            $product['sizes'] = array_values(array_unique($sizes));
            $product['colors'] = array_values(array_unique($colors));
        }

        unset($product);

        return $products;
    }

    /**
     * @param int[] $productIds
     * @return array<int, string>
     */
    private function getPrimaryImages(array $productIds): array
    {
        if ($productIds === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $sql = "SELECT product_id, image_url, is_primary
                FROM product_images
                WHERE product_id IN ($placeholders)
                ORDER BY is_primary DESC, image_id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($productIds);

        $images = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $image) {
            $productId = (int) $image['product_id'];

            if (!isset($images[$productId]) && $image['image_url'] !== '') {
                $images[$productId] = (string) $image['image_url'];
            }
        }

        return $images;
    }

    /**
     * @param int[] $productIds
     * @return array<int, array<int, array<string, mixed>>>
     */
    private function getAvailableVariants(array $productIds): array
    {
        if ($productIds === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $sql = "SELECT product_id, size, color, stock_quantity
                FROM product_variants
                WHERE product_id IN ($placeholders)
                AND stock_quantity > 0
                ORDER BY product_id ASC, size ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($productIds);

        $variants = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $variant) {
            $variants[(int) $variant['product_id']][] = $variant;
        }

        return $variants;
    }
}
