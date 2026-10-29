<?php

class User
{
    private PDO $db;

    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

   
    public function getAll(): array
    {
        $sql = "SELECT id, full_name, email, phone, created_at
                FROM users
                WHERE is_active = 1
                ORDER BY id DESC";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

 
    public function getById(int $id): ?array
    {
        $sql = "SELECT id, full_name, email, phone, created_at
                FROM users
                WHERE id = ? AND is_active = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $user = $stmt->fetch();

        return $user ?: null;
    }


    public function findByEmail(string $email): ?array
    {
        // Includes inactive users so a deactivated email can't be registered again
        $sql = "SELECT id, full_name, email, password_hash, phone, is_active, created_at
                FROM users
                WHERE email = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        return $user ?: null;
    }


    public function create(string $fullName, string $email, string $password, ?string $phone = null): int
    {
        $sql = "INSERT INTO users (full_name, email, password_hash, phone)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $fullName,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $phone
        ]);

        return (int) $this->db->lastInsertId();
    }


    public function update(int $id, string $fullName, string $email, ?string $phone = null): bool
    {
        $sql = "UPDATE users
                SET full_name = ?, email = ?, phone = ?
                WHERE id = ? AND is_active = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fullName, $email, $phone, $id]);

        return $stmt->rowCount() > 0;
    }


    public function updatePassword(int $id, string $newPassword): bool
    {
        $sql = "UPDATE users
                SET password_hash = ?
                WHERE id = ? AND is_active = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            password_hash($newPassword, PASSWORD_DEFAULT),
            $id
        ]);

        return $stmt->rowCount() > 0;
    }


    public function delete(int $id): bool
    {
        // Soft delete: keeps the user's orders, addresses and favorites intact
        $sql = "UPDATE users SET is_active = 0 WHERE id = ? AND is_active = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }
}
