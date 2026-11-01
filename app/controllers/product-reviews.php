<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../../public/session.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

function reviewResponse(int $status, array $data): never
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit();
}

function reviewJsonBody(): array
{
    try {
        $body = json_decode((string) file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        reviewResponse(400, ['success' => false, 'message' => 'The review request is not valid JSON.']);
    }
    if (!is_array($body)) {
        reviewResponse(400, ['success' => false, 'message' => 'The review request must be a JSON object.']);
    }
    return $body;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$productId = filter_var($_GET['product_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($productId === false) {
    reviewResponse(422, ['success' => false, 'message' => 'A valid product is required.']);
}

try {
    $pdo = (new Database())->connect();
    $productStmt = $pdo->prepare('SELECT id FROM products WHERE id = ?');
    $productStmt->execute([$productId]);
    if (!$productStmt->fetchColumn()) {
        reviewResponse(404, ['success' => false, 'message' => 'Product not found.']);
    }

    if ($method === 'GET') {
        $summaryStmt = $pdo->prepare('SELECT COUNT(*) AS review_count, COALESCE(AVG(rating), 0) AS average_rating FROM product_reviews WHERE product_id = ?');
        $summaryStmt->execute([$productId]);
        $summary = $summaryStmt->fetch(PDO::FETCH_ASSOC);
        $reviewsStmt = $pdo->prepare(
            'SELECT r.rating, r.review_text, DATE(r.created_at) AS created_at, u.full_name
             FROM product_reviews r JOIN users u ON u.id = r.user_id
             WHERE r.product_id = ? ORDER BY r.created_at DESC, r.id DESC LIMIT 50'
        );
        $reviewsStmt->execute([$productId]);
        reviewResponse(200, [
            'success' => true,
            'count' => (int) $summary['review_count'],
            'average' => (float) $summary['average_rating'],
            'reviews' => $reviewsStmt->fetchAll(PDO::FETCH_ASSOC),
        ]);
    }

    if ($method !== 'POST') {
        header('Allow: GET, POST');
        reviewResponse(405, ['success' => false, 'message' => 'Method not allowed.']);
    }

    $userId = filter_var($_SESSION['user_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($userId === false) {
        reviewResponse(401, ['success' => false, 'message' => 'Sign in to submit a review.']);
    }
    $body = reviewJsonBody();
    $csrfToken = (string) ($body['csrf_token'] ?? '');
    if (empty($_SESSION['csrf_token']) || !hash_equals((string) $_SESSION['csrf_token'], $csrfToken)) {
        reviewResponse(403, ['success' => false, 'message' => 'Your session expired. Refresh the page and try again.']);
    }

    $bodyProductId = filter_var($body['product_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $rating = filter_var($body['rating'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 5]]);
    $rawReviewText = $body['review_text'] ?? null;
    if (!is_string($rawReviewText)) {
        reviewResponse(422, ['success' => false, 'message' => 'Review text must be plain text.']);
    }
    $reviewText = trim(strip_tags($rawReviewText));
    if ($bodyProductId !== (int) $productId || $rating === false || $reviewText === '' || mb_strlen($reviewText) > 1200) {
        reviewResponse(422, ['success' => false, 'message' => 'Provide a rating from 1 to 5 and a review of up to 1,200 characters.']);
    }

    $purchaseStmt = $pdo->prepare(
        "SELECT 1 FROM orders o
         JOIN order_items oi ON oi.order_id = o.id
         LEFT JOIN payments p ON p.order_id = o.id
         WHERE o.user_id = ? AND oi.product_id = ?
           AND (p.payment_status = 'Paid' OR o.order_status = 'Delivered')
         LIMIT 1"
    );
    $purchaseStmt->execute([$userId, $productId]);
    if (!$purchaseStmt->fetchColumn()) {
        reviewResponse(403, ['success' => false, 'message' => 'Reviews are available after a completed purchase.']);
    }

    $insertStmt = $pdo->prepare('INSERT INTO product_reviews (product_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)');
    $insertStmt->execute([$productId, $userId, $rating, $reviewText]);
    reviewResponse(201, ['success' => true, 'message' => 'Your review has been published.']);
} catch (PDOException $e) {
    error_log($e->getMessage());
    if ($e->getCode() === '23000') {
        reviewResponse(409, ['success' => false, 'message' => 'You have already reviewed this product.']);
    }
    reviewResponse(500, ['success' => false, 'message' => 'Reviews are temporarily unavailable.']);
} catch (Throwable $e) {
    error_log($e->getMessage());
    reviewResponse(500, ['success' => false, 'message' => 'Reviews are temporarily unavailable.']);
}