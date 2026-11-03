
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

    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($images !== []) {
        return $images;
    }

    $productStmt = $this->db->prepare("SELECT image_url FROM products WHERE id = ?");
    $productStmt->execute([$productId]);
    $imageUrl = $productStmt->fetchColumn();
    if (!is_string($imageUrl) || !str_starts_with($imageUrl, "/Data/women/dress/")) {
        return [];
    }

    $relativePath = str_replace("\\", "/", ltrim($imageUrl, "/"));
    if (in_array("..", explode("/", $relativePath), true)) {
        return [];
    }

    $projectRoot = dirname(__DIR__, 2);
    $dressRoot = realpath($projectRoot . DIRECTORY_SEPARATOR . "Data" . DIRECTORY_SEPARATOR . "women" . DIRECTORY_SEPARATOR . "dress");
    $imageDirectory = realpath(dirname($projectRoot . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $relativePath)));
    if (
        $dressRoot === false
        || $imageDirectory === false
        || !str_starts_with($imageDirectory, $dressRoot . DIRECTORY_SEPARATOR)
    ) {
        return [];
    }

    $files = glob($imageDirectory . DIRECTORY_SEPARATOR . "*");
    if ($files === false) {
        throw new RuntimeException("Could not read the dress image directory.");
    }

    $imageFiles = array_values(array_filter(
        $files,
        static fn(string $file): bool => is_file($file)
            && preg_match('/\.(?:jpe?g|png|webp|gif)$/i', $file) === 1
    ));
    natsort($imageFiles);
    $imageFiles = array_values($imageFiles);
    $primaryImage = realpath(
        $projectRoot . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $relativePath)
    );
    if ($primaryImage !== false && in_array($primaryImage, $imageFiles, true)) {
        $imageFiles = array_merge(
            [$primaryImage],
            array_values(array_diff($imageFiles, [$primaryImage]))
        );
    }

    foreach ($imageFiles as $index => $file) {
        $images[] = [
            "image_id" => null,
            "product_id" => $productId,
            "image_url" => "/" . str_replace(DIRECTORY_SEPARATOR, "/", substr($file, strlen($projectRoot) + 1)),
            "is_primary" => $index === 0 ? 1 : 0,
        ];
    }

    return $images;
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
