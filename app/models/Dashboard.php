
<?php

class Dashboard
{
    private PDO $db;

    // Constructor
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Total profit from delivered orders
    public function getTotalRevenue(): float
    {
        $sql = "
            SELECT COALESCE(
                SUM((oi.unit_price - p.cost_price) * oi.quantity),
                0
            )
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.id
            JOIN products p ON p.id = oi.product_id
            WHERE o.order_status = 'Delivered'
        ";

        $stmt = $this->db->query($sql);

        return (float) $stmt->fetchColumn();
    }

    // Total Customers
    public function getTotalCustomers(): int
    {
        $sql = "SELECT COUNT(*) FROM users";

        $stmt = $this->db->query($sql);

        return (int) $stmt->fetchColumn();
    }

    // Total Products
    public function getTotalProducts(): int
    {
        $sql = "SELECT COUNT(*) FROM products";

        $stmt = $this->db->query($sql);

        return (int) $stmt->fetchColumn();
    }

    // Total Orders
    public function getTotalOrders(): int
    {
        $sql = "SELECT COUNT(*) FROM orders";

        $stmt = $this->db->query($sql);

        return (int) $stmt->fetchColumn();
    }

    
    
    // Top 5 products by number of orders
    public function getTopProductsByOrders(): array
    {
        $sql = "
            SELECT
                p.id,
                p.product_name AS name,
                COUNT(DISTINCT o.id) AS total_sold
            FROM products p
            JOIN order_items oi ON oi.product_id = p.id
            JOIN orders o ON o.id = oi.order_id
            WHERE o.order_status <> 'Cancelled'
            GROUP BY p.id, p.product_name
            ORDER BY total_sold DESC, p.id ASC
            LIMIT 5
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Monthly profit for the current year
    public function getMonthlyRevenue(): array
    {
        $sql = "
            SELECT
                MONTH(o.order_date) AS month_number,
                SUM(
                    (oi.unit_price - p.cost_price) * oi.quantity
                ) AS total_revenue
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.id
            JOIN products p ON p.id = oi.product_id
            WHERE o.order_status = 'Delivered'
              AND YEAR(o.order_date) = YEAR(CURDATE())
            GROUP BY MONTH(o.order_date)
            ORDER BY month_number
        ";

        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Start with zero profit for all 12 months
        $monthlyRevenue = array_fill(0, 12, 0);

        foreach ($results as $row) {
            $monthIndex = (int) $row['month_number'] - 1;

            $monthlyRevenue[$monthIndex] =
                (float) $row['total_revenue'];
        }

        return $monthlyRevenue;
    }



    // Top 5 products by gross profit
    public function getTopProductsByRevenue(): array
    {
        $sql = "
            SELECT
                p.id,
                p.product_name AS name,
                SUM(
                    (oi.unit_price - p.cost_price) * oi.quantity
                ) AS total_revenue
            FROM products p
            JOIN order_items oi ON oi.product_id = p.id
            JOIN orders o ON o.id = oi.order_id
            WHERE o.order_status = 'Delivered'
            GROUP BY p.id, p.product_name
            ORDER BY total_revenue DESC, p.id ASC
            LIMIT 5
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Orders grouped by country
    public function getOrdersByCountry(): array
    {
        $sql = "
            SELECT
                country,
                COUNT(*) AS total_orders,
                COALESCE(SUM(total_amount), 0) AS total_sales
            FROM orders
            WHERE order_status <> 'Cancelled'
            GROUP BY country
            ORDER BY total_orders DESC, country ASC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    
    // Get the latest 5 orders
    public function getRecentOrders(): array
    {
        $sql = "
            SELECT
                o.id,
                o.full_name,
                o.order_date,
                o.total_amount,
                o.order_status
            FROM orders o
            ORDER BY o.order_date DESC, o.id DESC
            LIMIT 5
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}
