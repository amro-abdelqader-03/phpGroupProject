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

}

?>