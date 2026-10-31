<?php

class categories
{

    private PDO $db;

    public function __construct(PDO $db)
    {


        $this->db = $db;

    }

    // Get all categories

    public function get_Allcategory(): array
    {
       $sql = "SELECT
            c.id,
            c.name,
            c.description,
            c.status,
            COUNT(p.id) AS products_count
        FROM categories AS c
        LEFT JOIN subcategories AS s
            ON c.id = s.category_id
        LEFT JOIN products AS p
            ON s.id = p.subcategory_id
        GROUP BY
            c.id,
            c.name,
            c.description,
            c.status
        ORDER BY c.id DESC";

$stmt = $this->db->query($sql);

return $stmt->fetchAll();


    }


       // Get categories by id 
    public function get_by_id (int $id): ?array {
        $sql = "  SELECT id, name, description ,status
                  FROM categories
                  WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $categorie = $stmt->fetch();

        return $categorie ?: null;

    }


 // create categories 

 public function create (string $name , string $description ,string $status): int{
     $sql = "INSERT INTO categories
                (name, description, status )
                VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $name,
            $description,
            $status,
        ]);

        return (int) $this->db->lastInsertId();
 }

 // Update category
 public function update (  int $id,string $name , string $description , string $status): bool{
     $sql = "UPDATE categories
                    SET name = ?,
                        description = ?,
                        status = ?
                    WHERE id = ?";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                $name,
                $description,
                $status,
                $id
            ]);
        return $stmt->rowCount() > 0;

 }

     // Delete category
  public function delete(int $id): bool {
    
        $sql = "DELETE FROM categories WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

}
