<?php
require_once __DIR__ . '/../../public/session.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../../stripe-php/stripe-php-master/init.php';

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit();
}

if (!isset($_SESSION['user_id'])) { 
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized. Please login first."]);
    exit();
}

$userId = $_SESSION["user_id"];

$name         = trim($_POST['name'] ?? '');
$phone        = trim($_POST['phone'] ?? '');
$city         = trim($_POST['city'] ?? '');
$area         = trim($_POST['area'] ?? '');
$street       = trim($_POST['street'] ?? '');
$building     = trim($_POST['building'] ?? '');
$apartment    = trim($_POST['apartment'] ?? '');
$instructions = trim($_POST['instructions'] ?? '');
$paymentMethod = trim($_POST['payment'] ?? '');

if (empty($name) || empty($phone) || empty($city) || empty($area) || empty($street) || empty($paymentMethod)) {
    http_response_code(422);
    echo json_encode(["success" => false, "message" => "Please fill in all required fields."]);
    exit();
}

$data = [
    'name'         => $name,
    'phone'        => $phone,
    'city'         => $city,
    'area'         => $area,
    'street'       => $street,
    'building'     => $building,
    'apartment'    => $apartment,
    'instructions' => $instructions
];

try {
    $database = new Database();
    $pdo = $database->connect();

    if ($paymentMethod === 'visa') {
\Stripe\Stripe::setApiKey('sk_test_51UWep4CDQG6C8EVj6v4II3pKd9KRnntvXBXBYOHBQxdUVAyFkJ7MLRci8SpR6Tu6dImQja5QqQjgxggcKdIulS6M009UphvI9I');
        $cartQuery = "SELECT ci.quantity, p.price, p.product_name 
                      FROM carts c 
                      JOIN cart_items ci ON c.id = ci.cart_id 
                      JOIN products p ON ci.product_id = p.id 
                      WHERE c.user_id = :user_id";
        $cartStmt = $pdo->prepare($cartQuery);
        $cartStmt->execute([':user_id' => $userId]);
        $cartItems = $cartStmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($cartItems)) {
            throw new Exception("Cart is empty");
        }

        $lineItems = [];
        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => 'usd',
                    'product_data' => [
                        'name' => $item['product_name'],
                    ],
                    'unit_amount'  => intval($item['price'] * 1000),
                ],
                'quantity'   => $item['quantity'],
            ];
        }

        $lineItems[] = [
            'price_data' => [
                'currency'     => 'usd',
                'product_data' => [
                    'name' => 'Shipping Cost',
                ],
                'unit_amount'  => intval(5.00 * 1000),
            ],
            'quantity'   => 1,
        ];

$checkoutSession = \Stripe\Checkout\Session::create([
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => 'http://localhost/phpGroupProject/public/order-success.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => 'http://localhost/phpGroupProject/public/checkout.php',
        ]);

        http_response_code(200);
        echo json_encode([
            "success"      => true,
            "redirect_url" => $checkoutSession->url
        ]);
        exit();

    } else {
        $orderId = OrderModel::createOrderAndPayment($pdo, $userId, $data, $paymentMethod);
        
        if ($orderId) {
            http_response_code(200);
            echo json_encode([
                "success"      => true,
                "message"      => "Order placed successfully",
                "order_id"     => $orderId,
                "redirect_url" => "order-success.php?order_id=" . $orderId
            ]);
            exit();
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to save order in database."]);
            exit();
        }
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "SQL Error: " . $e->getMessage()]);
    exit();
}