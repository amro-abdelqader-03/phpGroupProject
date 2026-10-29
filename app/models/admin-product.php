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
        $sql = "SELECT id, subcategory_id, product_name, price, cost_price, description
                FROM products
                ORDER BY id DESC";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT id, subcategory_id, product_name, price, cost_price, description
                FROM products
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $product = $stmt->fetch();

        return $product ?: null;
    }

    public function create(
        string $name,
        string $description,
        int $subcategory,
        float $price_cost,
        float $price,
    ): int {


        $sql = "INSERT INTO products
                (name, description, subcategory_id, price)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $name,
            $description,
            $subcategory,
            $price_cost,
            $price
        ]);

        return (int) $this->db->lastInsertId();
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