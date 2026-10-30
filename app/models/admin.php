
<?php

class Admin
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Find admin by email
    public function findByEmail(string $email): array|false
    {
        $sql = "SELECT id, full_name, email, password_hash
                FROM admin
                WHERE email = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
