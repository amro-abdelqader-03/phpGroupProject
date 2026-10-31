<?php

class Accessories{
    private PDO $db;
     public function __construct(PDO $db){
        $this->db = $db;
     }

     //(read) Get the products of subcategories
     public function getBySubcategories(array $subcategoryIds):array{
        
     //if no ids given: nothing to look for 
     if(count($subcategoryIds)==0){
        return [];
     }
     
     // Make one ? for each id: [15, 16, 17] -> "?, ?, ?"
     $placeholders=implode(",",array_fill(0,count($subcategoryIds),"?"));

        $sql = "SELECT p.id, p.product_name, p.description, p.price,
                       p.image_url, p.stock_quantity,
                       p.subcategory_id, s.name AS subcategory_name
                FROM products p
                JOIN subcategories s ON s.id = p.subcategory_id
                WHERE p.subcategory_id IN ($placeholders)
                ORDER BY p.subcategory_id, p.id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($subcategoryIds);

        return $stmt->fetchAll();
     }

         // (read) Get subcategories by id, for the category cards
    // Example: getSubcategories([15, 16, 17, 18]) -> Derby, Loafers, Monk Strap, Oxford
    public function getSubcategories(array $subcategoryIds): array
    {
        if (count($subcategoryIds) === 0) {
            return [];
        }

        $subcategoryIds = array_map("intval", $subcategoryIds);   // make sure they are numbers

        $placeholders = implode(", ", array_fill(0, count($subcategoryIds), "?"));

        $sql = "SELECT id, name, description, image_url
                FROM subcategories
                WHERE id IN ($placeholders)
                ORDER BY id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($subcategoryIds);

        return $stmt->fetchAll();
    }

    // (read) Get one product by its id, for the product page (accessory.php?id=22)
    // Returns null if there is no product with this id
    public function getById(int $id): ?array
    {
        $sql = "SELECT p.id, p.product_name, p.description, p.price,
                       p.image_url, p.stock_quantity,
                       p.subcategory_id, s.name AS subcategory_name
                FROM products p
                JOIN subcategories s ON s.id = p.subcategory_id
                WHERE p.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $product = $stmt->fetch();

        if ($product === false) {
            return null;
        }

        return $product;
    }

    // (read) Get the sizes of products (from the product_variants table)
    // Example: getSizes([22, 23]) -> [22 => [["size" => "38", "stock_quantity" => 2], ...], 23 => [...]]
    // Products without sizes (ties, cuff links...) are simply not in the result
    public function getSizes(array $productIds): array
    {
        if (count($productIds) === 0) {
            return [];
        }

        $productIds = array_map("intval", $productIds);   // make sure they are numbers

        $placeholders = implode(", ", array_fill(0, count($productIds), "?"));

        $sql = "SELECT product_id, size, stock_quantity
                FROM product_variants
                WHERE product_id IN ($placeholders)
                  AND size IS NOT NULL AND size <> ''
                ORDER BY product_id, id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($productIds);

        // Group the rows by product id
        $sizes = [];
        foreach ($stmt->fetchAll() as $row) {
            $sizes[$row["product_id"]][] = [
                "size" => $row["size"],
                "stock_quantity" => $row["stock_quantity"],
            ];
        }

        return $sizes;
    }

}

?>