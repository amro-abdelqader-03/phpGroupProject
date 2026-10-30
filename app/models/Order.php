<?php
require_once __DIR__ . '/../config/Database.php';

class Order {
    private PDO $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAllOrders(): array {
        $stmt = $this->db->query("
            SELECT o.id, o.user_id, o.total_amount, o.order_status, o.created_at, o.delivery_date,
                   COALESCE(u.full_name, 'Guest Customer') AS full_name 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function deleteOrder(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM orders WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getOrdersPaginated($limit, $offset, $search = ''): array {
        $searchWildcard = "%{$search}%";
        
        $stmt = $this->db->prepare("
            SELECT o.id, o.user_id, o.total_amount, o.order_status, o.created_at, o.delivery_date,
                   COALESCE(u.full_name, 'Guest Customer') AS full_name 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            WHERE o.id LIKE ? OR u.full_name LIKE ?
            ORDER BY o.created_at DESC 
            LIMIT ? OFFSET ?
        ");
        
        $stmt->bindValue(1, $searchWildcard, PDO::PARAM_STR);
        $stmt->bindValue(2, $searchWildcard, PDO::PARAM_STR);
        $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(4, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalOrdersCount($search = ''): int {
        $searchWildcard = "%{$search}%";
        
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            WHERE o.id LIKE ? OR u.full_name LIKE ?
        ");
        
        $stmt->bindValue(1, $searchWildcard, PDO::PARAM_STR);
        $stmt->bindValue(2, $searchWildcard, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }
}