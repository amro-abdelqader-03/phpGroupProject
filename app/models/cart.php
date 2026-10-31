<?php

class Cart
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(int $userId): int
    {
        $stmt = $this->db->prepare('INSERT INTO carts (user_id) VALUES (?)');
        $stmt->execute([$userId]);
        return (int) $this->db->lastInsertId();
    }

    public function getByID(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, user_id FROM carts WHERE id = ?');
        $stmt->execute([$id]);
        $cart = $stmt->fetch();
        return $cart ?: null;
    }

    public function getByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT id, user_id FROM carts WHERE user_id = ? ORDER BY id DESC LIMIT 1');
        $stmt->execute([$userId]);
        $cart = $stmt->fetch();
        return $cart ?: null;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM carts WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
