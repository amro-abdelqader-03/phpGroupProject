
<?php

require_once __DIR__ . '/../models/Dashboard.php';

class AdminDashboardController
{
    private Dashboard $dashboard;

    public function __construct(PDO $db)
    {
        $this->dashboard = new Dashboard($db);
    }

    public function getStatistics(): array
    {
        return [
            'revenue' => $this->dashboard->getTotalRevenue(),
            'customers' => $this->dashboard->getTotalCustomers(),
            'products' => $this->dashboard->getTotalProducts(),
            'orders' => $this->dashboard->getTotalOrders()
        ];
    }

    // Top selling products for chart
    public function getTopProductsByOrders(): array
    {
        return $this->dashboard->getTopProductsByOrders();
    }

    // Monthly revenue for Revenue Overview chart
    public function getMonthlyRevenue(): array
    {
        return $this->dashboard->getMonthlyRevenue();
    }



    
    // Get top 5 products by gross profit
    public function getTopProductsByRevenue(): array
    {
        return $this->dashboard->getTopProductsByRevenue();
    }




    
    // Get orders grouped by country
    public function getOrdersByCountry(): array
    {
        return $this->dashboard->getOrdersByCountry();
    }


    
    // Get latest 5 orders
    public function getRecentOrders(): array
    {
        return $this->dashboard->getRecentOrders();
    }


}
