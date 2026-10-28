<?php
class Admin_product{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Get all students (never return passwords)
    public function getAll(): array
    {
        $sql = "SELECT id, name, description, category, qty, status, price
                FROM products
                ORDER BY id DESC";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    // Get student by ID (never return password)
    public function getById(int $id): ?array
    {
        $sql = "SELECT id, name, description, category, qty, status, price
                FROM products
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $student = $stmt->fetch();

        return $student ?: null;
    }

    // Create student
    public function create(
        string $name,
        string $description,
        string $category,
        string $subcategory,
        int $price,
        int $images_id,
    ): int {
        // Hash password before saving
        

        $sql = "INSERT INTO products
                (name, description, category, subcategory, price, images_id)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $name,
            $description,
            $category,
            $subcategory,
            $price,
            $images_id
        ]);

        return (int) $this->db->lastInsertId();
    }

    // Update student
    // Password is optional when updating
    public function update(
        int $id,
        string $name,
        string $email,
        string $course,
        ?string $password = null
    ): bool {
        if ($password !== null && $password !== "") {
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = "UPDATE students
                    SET name = ?,
                        email = ?,
                        course = ?,
                        password = ?
                    WHERE id = ?";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                $name,
                $email,
                $course,
                $hashedPassword,
                $id
            ]);
        } else {
            $sql = "UPDATE students
                    SET name = ?,
                        email = ?,
                        course = ?
                    WHERE id = ?";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                $name,
                $email,
                $course,
                $id
            ]);
        }

        return $stmt->rowCount() > 0;
    }

    // Verify password (for future login functionality)
    public function verifyPassword(
        int $id,
        string $password
    ): bool {
        $sql = "SELECT password
                FROM students
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $student = $stmt->fetch();

        if (!$student) {
            return false;
        }

        return password_verify(
            $password,
            $student["password"]
        );
    }

    // Delete student
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM students WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    } 
}
?>