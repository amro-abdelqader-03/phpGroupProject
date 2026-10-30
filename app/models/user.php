<?php

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Create a new user (used by the "Add User" button)
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





    // (read)Get users for the admin table (search, filters and pages)
    // $status: null = all, 1 = active, 0 = inactive
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

    // Count users that match the filters (for "Showing 1-5 of 25" and the page numbers)
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

    // Countries for the "All Locations" dropdown
    public function getCountries(): array
    {
        $sql = "SELECT DISTINCT country
                FROM address
                ORDER BY country";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Get one user (for the view and edit icons), never returns the password
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

    // Find a user by email (to stop duplicate emails when adding or editing)
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT id, full_name, email, phone, is_active
                FROM users
                WHERE email = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    // (update) Edit a user's details (used by the pencil icon)
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

    // (delete)Change a user's status (used by the Active / Inactive dropdown)
    // $isActive: 1 = active, 0 = inactive
    public function setStatus(int $id, int $isActive): bool
    {
        $sql = "UPDATE users
                SET is_active = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$isActive ? 1 : 0, $id]);

        return $stmt->rowCount() > 0;
    }

    // (read) All addresses of one user (for the view page)
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

    // (read) Latest orders of one user (for the view page)
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

    // Builds the WHERE part shared by getAll() and countAll()
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
