<?php
require_once __DIR__ . '/../models/Order.php';

class OrderController {
    private Order $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
            try {
                $deleted = $this->orderModel->deleteOrder((int)$_POST['delete_order_id']);
                $status = $deleted ? 'deleted' : 'protected';
            } catch (PDOException $e) {
                error_log($e->getMessage());
                $status = 'failed';
            }
            header("Location: orders.php?delete=" . $status);
            exit();
        }

        $orders = $this->orderModel->getAllOrders();

        include __DIR__ . '/../../admin/orders.php';
    }
}