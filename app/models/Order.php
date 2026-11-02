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
        $this->db->beginTransaction();

        try {
            $orderStmt = $this->db->prepare("SELECT id FROM orders WHERE id = ? FOR UPDATE");
            $orderStmt->execute([$id]);
            if (!$orderStmt->fetchColumn()) {
                $this->db->commit();
                return false;
            }

            $paymentStmt = $this->db->prepare("SELECT 1 FROM payments WHERE order_id = ? LIMIT 1");
            $paymentStmt->execute([$id]);
            if ($paymentStmt->fetchColumn()) {
                $this->db->commit();
                return false;
            }

            $deleteStmt = $this->db->prepare("DELETE FROM orders WHERE id = ?");
            $deleteStmt->execute([$id]);
            $deleted = $deleteStmt->rowCount() === 1;

            $this->db->commit();
            return $deleted;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function getOrdersPaginated(
        int $limit,
        int $offset,
        string $search = '',
        string $status = 'all',
        string $sort = 'newest'
    ): array {
        [$where, $parameters] = $this->buildOrderFilters($search, $status);
        $orderBy = $sort === 'revenue'
            ? 'COALESCE(o.revenue, o.total_amount) DESC, o.order_date DESC'
            : 'o.order_date DESC';

        $stmt = $this->db->prepare("
            SELECT o.id, o.user_id, o.total_amount, o.order_status,
                   o.order_date AS created_at,
                   COALESCE(u.full_name, 'Guest Customer') AS full_name
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            $where
            ORDER BY $orderBy
            LIMIT ? OFFSET ?
        ");

        foreach ($parameters as $index => $parameter) {
            $stmt->bindValue($index + 1, $parameter, PDO::PARAM_STR);
        }
        $stmt->bindValue(count($parameters) + 1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(count($parameters) + 2, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalOrdersCount(string $search = '', string $status = 'all'): int {
        [$where, $parameters] = $this->buildOrderFilters($search, $status);
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            $where
        ");

        foreach ($parameters as $index => $parameter) {
            $stmt->bindValue($index + 1, $parameter, PDO::PARAM_STR);
        }
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    private function buildOrderFilters(string $search, string $status): array {
        $conditions = [];
        $parameters = [];
        $search = trim($search);

        if ($search !== '') {
            $conditions[] = '(CAST(o.id AS CHAR) LIKE ? OR u.full_name LIKE ?)';
            $searchWildcard = "%{$search}%";
            $parameters[] = $searchWildcard;
            $parameters[] = $searchWildcard;
        }

        $status = strtolower(trim($status));
        if (in_array($status, ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'], true)) {
            $conditions[] = 'LOWER(o.order_status) = ?';
            $parameters[] = $status;
        }

        return [
            $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '',
            $parameters,
        ];
    }
}