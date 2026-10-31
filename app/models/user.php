<?php

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(
        string $fullName,
        string $email,
        string $password,
        ?string $phone = null
    ): int {
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

    // Get users for the admin table
    public function getAll(
        string $search = "",
        ?int $status = null,
        string $country = "",
        int $limit = 5,
        int $offset = 0
    ): array {
        [$where, $params] = $this->buildFilters($search, $status, $country);

        $sql = "SELECT u.id, u.full_name, u.email, u.phone, u.is_active, u.created_at,
                       (SELECT COUNT(*) FROM orders o WHERE o.user_id = u.id) AS total_orders,
                       (SELECT a.country FROM address a WHERE a.user_id = u.id
                        ORDER BY a.id LIMIT 1) AS country
                FROM users u
                $where
                ORDER BY u.id DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($params as $param) {
            $stmt->bindValue($i++, $param);
        }
        $stmt->bindValue($i++, $limit, PDO::PARAM_INT);
        $stmt->bindValue($i, $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countAll(
        string $search = "",
        ?int $status = null,
        string $country = ""
    ): int {
        [$where, $params] = $this->buildFilters($search, $status, $country);

        $sql = "SELECT COUNT(*) FROM users u $where";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function getCountries(): array
    {
        $sql = "SELECT DISTINCT country
                FROM address
                ORDER BY country";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT u.id, u.full_name, u.email, u.phone, u.is_active, u.created_at,
                       (SELECT COUNT(*) FROM orders o WHERE o.user_id = u.id) AS total_orders,
                       (SELECT a.country FROM address a WHERE a.user_id = u.id
                        ORDER BY a.id LIMIT 1) AS country
                FROM users u
                WHERE u.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT id, full_name, email, password_hash, phone
                FROM users
                WHERE email = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function update(
        int $id,
        string $fullName,
        string $email,
        ?string $phone = null
    ): bool {
        $sql = "UPDATE users
                SET full_name = ?, email = ?, phone = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fullName, $email, $phone, $id]);

        return $stmt->rowCount() > 0;
    }

    public function setStatus(int $id, int $isActive): bool
    {
        $sql = "UPDATE users
                SET is_active = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$isActive ? 1 : 0, $id]);

        return $stmt->rowCount() > 0;
    }

    public function getAddresses(int $userId): array
    {
        $sql = "SELECT id, full_name, country, city, street, building, phone
                FROM address
                WHERE user_id = ?
                ORDER BY id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function getOrders(int $userId, int $limit = 10): array
    {
        $sql = "SELECT id, order_date, total_amount, order_status
                FROM orders
                WHERE user_id = ?
                ORDER BY order_date DESC, id DESC
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function buildFilters(string $search, ?int $status, string $country): array
    {
        $conditions = [];
        $params = [];

        if ($search !== "") {
            $conditions[] = "(u.full_name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $like = "%" . $search . "%";
            array_push($params, $like, $like, $like);
        }

        if ($status !== null) {
            $conditions[] = "u.is_active = ?";
            $params[] = $status;
        }

        if ($country !== "") {
            $conditions[] = "(SELECT a.country FROM address a WHERE a.user_id = u.id
                              ORDER BY a.id LIMIT 1) = ?";
            $params[] = $country;
        }

        $where = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

        return [$where, $params];
    }
}