<?php
require_once __DIR__ . '/../../public/session.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Env.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../../stripe-php/stripe-php-master/init.php';

header("Content-Type: application/json; charset=UTF-8");

function checkoutPostString(string $field): string
{
    $value = $_POST[$field] ?? '';
    if (!is_string($value)) {
        http_response_code(422);
        echo json_encode(["success" => false, "message" => "One or more checkout fields have an invalid format."]);
        exit();
    }
    return trim($value);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit();
}

if (!isset($_SESSION['user_id']) || filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized. Please login first."]);
    exit();
}

$userId = (int) $_SESSION['user_id'];
$csrfToken = is_string($_POST['csrf_token'] ?? null) ? $_POST['csrf_token'] : '';
if (empty($_SESSION['csrf_token']) || !hash_equals((string) $_SESSION['csrf_token'], $csrfToken)) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Your checkout session expired. Refresh the page and try again."]);
    exit();
}
if (
    empty($_SESSION['checkout_nonce'])
    || !is_string($_POST['checkout_nonce'] ?? null)
    || !hash_equals((string) $_SESSION['checkout_nonce'], $_POST['checkout_nonce'])
) {
    http_response_code(409);
    echo json_encode(["success" => false, "message" => "This checkout was already submitted. Refresh the page to start a new order."]);
    exit();
}

$name = checkoutPostString('name');
$phone = checkoutPostString('phone');
$city = checkoutPostString('city');
$area = checkoutPostString('area');
$street = checkoutPostString('street');
$building = checkoutPostString('building');
$apartment = checkoutPostString('apartment');
$instructions = checkoutPostString('instructions');
$paymentMethod = checkoutPostString('payment');

$lengths = [
    'name' => mb_strlen($name),
    'phone' => mb_strlen($phone),
    'city' => mb_strlen($city),
    'area' => mb_strlen($area),
    'street' => mb_strlen($street),
    'building' => mb_strlen($building),
    'apartment' => mb_strlen($apartment),
    'instructions' => mb_strlen($instructions),
];

if (
    $name === '' || $phone === '' || $city === '' || $area === '' || $street === ''
    || !in_array($paymentMethod, ['cash-on-delivery', 'visa'], true)
    || $lengths['name'] > 100 || $lengths['phone'] > 20 || $lengths['city'] > 100
    || $lengths['area'] > 100 || $lengths['street'] > 200 || $lengths['building'] > 100
    || $lengths['apartment'] > 100 || $lengths['instructions'] > 255
    || mb_strlen('Street: ' . $street . ', Building: ' . $building . ', Apartment: ' . $apartment) > 255
    || !preg_match('/^[\p{L}\p{M} .\'-]{2,100}$/u', $name)
    || preg_match('/[^0-9+().\-\s]/', $phone)
    || strlen(preg_replace('/\D/', '', $phone)) < 7
    || strlen(preg_replace('/\D/', '', $phone)) > 15
) {
    http_response_code(422);
    echo json_encode(["success" => false, "message" => "Check the required delivery details and try again."]);
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
        Env::load(dirname(__DIR__, 2) . '/.env');
        $stripeSecret = Env::get('STRIPE_SECRET_KEY');
        if (!$stripeSecret || !preg_match('/^sk_(test|live)_[A-Za-z0-9]+$/', $stripeSecret)) {
            http_response_code(503);
            echo json_encode(["success" => false, "message" => "Stripe is not configured. Copy .env.example to the project root as .env and set STRIPE_SECRET_KEY to your Stripe test secret key (sk_test_...)."]);
            exit();
        }
        \Stripe\Stripe::setApiKey($stripeSecret);

        $order = OrderModel::createStripeOrder($pdo, $userId, $data);
        $lineItems = [];
        foreach ($order['items'] as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => 'usd',
                    'product_data' => [
                        'name' => $item['product_name'],
                    ],
                    'unit_amount'  => (int) round((float) $item['price'] * 100),
                ],
                'quantity'   => (int) $item['quantity'],
            ];
        }

        $lineItems[] = [
            'price_data' => [
                'currency'     => 'usd',
                'product_data' => [
                    'name' => 'Delivery',
                ],
                'unit_amount'  => (int) round($order['shipping'] * 100),
            ],
            'quantity'   => 1,
        ];

        $baseUrl = rtrim((string) Env::get('APP_BASE_URL', 'http://localhost/phpGroupProject/public'), '/');
        $checkoutSession = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'client_reference_id' => (string) $order['order_id'],
            'customer_email' => $_SESSION['user_email'] ?? null,
            'metadata' => ['order_id' => (string) $order['order_id'], 'user_id' => (string) $userId],
            'payment_intent_data' => ['metadata' => ['order_id' => (string) $order['order_id'], 'user_id' => (string) $userId]],
            'success_url' => $baseUrl . '/order-success.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $baseUrl . '/checkout.php?payment=cancelled',
        ]);
        OrderModel::saveStripeSession($pdo, $order['order_id'], $checkoutSession->id);
        unset($_SESSION['checkout_nonce']);

        http_response_code(200);
        echo json_encode([
            "success"      => true,
            "redirect_url" => $checkoutSession->url
        ]);
        exit();

    } else {
        $orderId = OrderModel::createOrderAndPayment($pdo, $userId, $data, $paymentMethod);
        
        if ($orderId) {
            unset($_SESSION['checkout_nonce']);
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

} catch (DomainException $e) {
    http_response_code(422);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit();
} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode([
        "success" => false, 
        "message" => "Error: " . $e->getMessage() . " in " . basename($e->getFile()) . " on line " . $e->getLine()
    ]);
    exit();
}