<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Gemini.php';
require_once __DIR__ . '/../models/ai-recommendation.php';
require_once __DIR__ . '/../../core/Response.php';
require_once __DIR__ . '/../../public/session.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

class AiServiceException extends RuntimeException
{
    public function __construct(string $message, public readonly int $status)
    {
        parent::__construct($message);
    }
}

function getJsonBody(): array
{
    $rawBody = file_get_contents('php://input');

    try {
        $data = json_decode($rawBody, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        Response::json(400, [
            'success' => false,
            'message' => 'Invalid JSON body',
        ]);
    }

    if (!is_array($data)) {
        Response::json(400, [
            'success' => false,
            'message' => 'JSON body must be a JSON object',
        ]);
    }

    return $data;
}

function requiredNumber(
    array $data,
    string $field,
    string $label,
    float $minimum,
    float $maximum
): float {
    if (!array_key_exists($field, $data)) {
        Response::json(422, [
            'success' => false,
            'message' => "$label is required",
        ]);
    }

    $value = $data[$field];

    if (is_bool($value) || (!is_int($value) && !is_float($value) && !is_string($value))) {
        Response::json(422, [
            'success' => false,
            'message' => "$label must be a number",
        ]);
    }

    if (!is_numeric($value)) {
        Response::json(422, [
            'success' => false,
            'message' => "$label must be a number",
        ]);
    }

    $number = (float) $value;

    if (!is_finite($number) || $number < $minimum || $number > $maximum) {
        Response::json(422, [
            'success' => false,
            'message' => "$label must be between $minimum and $maximum",
        ]);
    }

    return $number;
}

function validatePreferences(array $body): array
{
    $productType = $body['product_type'] ?? null;

    if (!is_string($productType)) {
        Response::json(422, [
            'success' => false,
            'message' => 'Product type is required',
        ]);
    }

    $productType = strtolower(trim($productType));

    if (!in_array($productType, ['suit', 'dress'], true)) {
        Response::json(422, [
            'success' => false,
            'message' => 'Product type must be either suit or dress',
        ]);
    }

    return [
        'product_type' => $productType,
        'height' => requiredNumber($body, 'height', 'Height', 100, 250),
        'width' => requiredNumber($body, 'width', 'Width', 30, 200),
        'budget' => requiredNumber($body, 'budget', 'Maximum budget', 0.01, 1000000),
    ];
}

/**
 * @param array<int, array<string, mixed>> $products
 * @return array<int, array<string, mixed>>
 */
function productsForPrompt(array $products): array
{
    return array_map(static function (array $product): array {
        $description = trim((string) $product['description']);

        if (mb_strlen($description) > 280) {
            $description = mb_substr($description, 0, 277) . '...';
        }

        return [
            'product_id' => (int) $product['product_id'],
            'product_name' => (string) $product['product_name'],
            'description' => $description,
            'price' => round((float) $product['price'], 2),
            'category' => (string) $product['category_name'],
            'subcategory' => (string) $product['subcategory_name'],
            'available_sizes' => $product['sizes'],
            'available_colors' => $product['colors'],
            'in_stock' => (int) $product['stock_quantity'] > 0,
        ];
    }, $products);
}

function systemInstruction(): string
{
    return <<<'PROMPT'
You are a careful personal stylist for Élan Bridal, a wedding store selling men's suits and women's dresses.

Follow every rule below:
1. Recommend ONLY products in the provided "products" array, and only use their exact product_id values. Never invent a product, product ID, name, price, size, colour, or availability.
2. Never recommend a product whose price is greater than the user's maximum budget.
3. Never recommend a product of the wrong type. A "suit" request must receive suits; a "dress" request must receive dresses.
4. Use the supplied description, subcategory, price, sizes and colours to explain why each item is a good match.
5. Return at most 4 recommendations, ordered from best to least suitable. If none are suitable, return success as true with an empty recommendations array and a clear message.
6. Height and width are preferences used to help choose a style or available size. They are not proof of fit. Never claim that a product will fit the user perfectly or guarantee a size.
7. If sizing data is missing or limited, say that the recommendation is an estimate and tell the user to check the product's size information.
8. Keep each reason short (no more than 220 characters) and factual.
9. Return only valid JSON matching the supplied response schema. Do not use markdown fences or add commentary outside the JSON.
PROMPT;
}

function geminiRequestBody(array $preferences, array $products): array
{
    $payload = [
        'preferences' => [
            'product_type' => $preferences['product_type'],
            'height_cm' => $preferences['height'],
            'width_cm' => $preferences['width'],
            'maximum_budget' => $preferences['budget'],
            'currency' => 'USD',
        ],
        'products' => productsForPrompt($products),
    ];

    $userText = "Use these preferences and database products to recommend the best matches:\n"
        . json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

    return [
        'systemInstruction' => [
            'parts' => [
                ['text' => systemInstruction()],
            ],
        ],
        'contents' => [
            [
                'role' => 'user',
                'parts' => [
                    ['text' => $userText],
                ],
            ],
        ],
        'generationConfig' => [
            'temperature' => 0.35,
            'maxOutputTokens' => 2048,
            'responseMimeType' => 'application/json',
            'responseSchema' => [
                'type' => 'OBJECT',
                'properties' => [
                    'success' => ['type' => 'BOOLEAN'],
                    'message' => ['type' => 'STRING'],
                    'recommendations' => [
                        'type' => 'ARRAY',
                        'items' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'product_id' => ['type' => 'INTEGER'],
                                'reason' => ['type' => 'STRING'],
                            ],
                            'required' => ['product_id', 'reason'],
                        ],
                    ],
                ],
                'required' => ['success', 'message', 'recommendations'],
            ],
        ],
    ];
}

function callGemini(array $requestBody): string
{
    $apiKey = GeminiConfig::apiKey();

    if ($apiKey === '') {
        throw new AiServiceException(
            'AI recommendations are not configured yet. Please try again later.',
            503
        );
    }

    if (!function_exists('curl_init')) {
        throw new AiServiceException(
            'AI recommendations are temporarily unavailable. Please try again later.',
            503
        );
    }

    $url = GeminiConfig::apiBase()
        . '/models/' . rawurlencode(GeminiConfig::model())
        . ':generateContent';

    try {
        $encodedBody = json_encode(
            $requestBody,
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    } catch (JsonException $e) {
        throw new AiServiceException(
            'AI recommendations are temporarily unavailable. Please try again later.',
            503
        );
    }

    $curl = curl_init($url);

    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT => GeminiConfig::timeoutSeconds(),
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'x-goog-api-key: ' . $apiKey,
        ],
        CURLOPT_POSTFIELDS => $encodedBody,
    ]);

    $rawResponse = curl_exec($curl);
    $curlError = curl_error($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

    curl_close($curl);

    if ($rawResponse === false) {
        error_log('Gemini request failed: ' . $curlError);

        throw new AiServiceException(
            'The AI recommendation service did not respond in time. Please try again.',
            503
        );
    }

    if ($status < 200 || $status >= 300) {
        error_log('Gemini API HTTP ' . $status . ': ' . substr((string) $rawResponse, 0, 1000));

        throw new AiServiceException(
            'The AI recommendation service is unavailable right now. Please try again later.',
            503
        );
    }

    try {
        $response = json_decode((string) $rawResponse, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        error_log('Gemini returned invalid JSON: ' . substr((string) $rawResponse, 0, 1000));

        throw new AiServiceException(
            'The AI recommendation service returned an invalid response. Please try again.',
            502
        );
    }

    $parts = $response['candidates'][0]['content']['parts'] ?? null;

    if (!is_array($parts)) {
        error_log('Gemini response contained no content: ' . substr((string) $rawResponse, 0, 1000));

        throw new AiServiceException(
            'The AI could not produce a recommendation. Please adjust your preferences and try again.',
            502
        );
    }

    $text = '';

    foreach ($parts as $part) {
        if (
            is_array($part)
            && isset($part['text'])
            && is_string($part['text'])
            && empty($part['thought'])
        ) {
            $text .= $part['text'];
        }
    }

    if (trim($text) === '') {
        throw new AiServiceException(
            'The AI could not produce a recommendation. Please adjust your preferences and try again.',
            502
        );
    }

    return $text;
}

function decodeAiResponse(string $text): array
{
    $text = trim($text);

    if (str_starts_with($text, "\xEF\xBB\xBF")) {
        $text = substr($text, 3);
    }

    if (preg_match('/^```(?:json)?\s*(.*?)\s*```$/is', $text, $matches)) {
        $text = $matches[1];
    }

    try {
        $decoded = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        error_log('Gemini recommendation JSON could not be decoded: ' . mb_substr($text, 0, 1000));

        throw new AiServiceException(
            'The AI returned an unreadable recommendation. Please try again.',
            502
        );
    }

    if (!is_array($decoded) || !isset($decoded['recommendations']) || !is_array($decoded['recommendations'])) {
        throw new AiServiceException(
            'The AI returned an unexpected recommendation format. Please try again.',
            502
        );
    }

    return $decoded;
}

/**
 * @param array<int, array<string, mixed>> $products
 * @return array<int, array<string, mixed>>
 */
function verifiedRecommendations(
    array $aiResponse,
    array $products,
    float $budget,
    string $productType
): array {
    $productsById = [];

    foreach ($products as $product) {
        $productsById[(int) $product['product_id']] = $product;
    }

    $recommendations = [];
    $seen = [];
    $rawRecommendations = $aiResponse['recommendations'];

    foreach ($rawRecommendations as $item) {
        if (count($recommendations) >= 4) {
            break;
        }

        if (!is_array($item)) {
            continue;
        }

        $rawId = $item['product_id'] ?? null;

        if (is_int($rawId)) {
            $productId = $rawId;
        } elseif (is_float($rawId) && floor($rawId) === $rawId) {
            $productId = (int) $rawId;
        } elseif (is_string($rawId) && ctype_digit($rawId)) {
            $productId = (int) $rawId;
        } else {
            continue;
        }

        if ($productId < 1 || !isset($productsById[$productId]) || isset($seen[$productId])) {
            continue;
        }

        $product = $productsById[$productId];

        // Re-check the business rules against the verified database record.
        if ((float) $product['price'] > $budget + 0.0001) {
            continue;
        }

        $reason = is_string($item['reason'] ?? null) ? $item['reason'] : '';
        $reason = strip_tags($reason);
        $reason = preg_replace('/\s+/u', ' ', $reason) ?? '';
        $reason = trim($reason);

        if (mb_strlen($reason) > 260) {
            $reason = rtrim(mb_substr($reason, 0, 257)) . '...';
        }

        if ($reason === '') {
            $typeLabel = $productType === 'suit' ? 'suit' : 'dress';
            $reason = 'This ' . $product['subcategory_name']
                . ' is within your maximum budget and matches your selected ' . $typeLabel . '.';
        }

        $seen[$productId] = true;

        $recommendations[] = [
            'product_id' => $productId,
            'product_name' => (string) $product['product_name'],
            'description' => (string) $product['description'],
            'price' => (float) $product['price'],
            'currency' => 'USD',
            'image_url' => $product['image_url'],
            'category' => (string) $product['category_name'],
            'subcategory' => (string) $product['subcategory_name'],
            'sizes' => $product['sizes'],
            'colors' => $product['colors'],
            'availability' => (int) $product['stock_quantity'] > 0 ? 'In stock' : 'Unavailable',
            'reason' => $reason,
        ];
    }

    if ($rawRecommendations !== [] && $recommendations === []) {
        error_log('Gemini returned recommendations that could not be verified against the database.');

        throw new AiServiceException(
            'We could not verify the AI recommendations. Please try again.',
            502
        );
    }

    return $recommendations;
}

function fitNote(array $products): string
{
    $hasSizing = false;

    foreach ($products as $product) {
        if (($product['sizes'] ?? []) !== []) {
            $hasSizing = true;
            break;
        }
    }

    if (!$hasSizing) {
        return 'The matching products do not include detailed sizing information in our catalogue, '
            . 'so this recommendation is an estimate. Please check each product\'s size information before ordering.';
    }

    return 'Height and width are used only as style and size preferences; they do not guarantee fit. '
        . 'Please check the product\'s size information before ordering.';
}

function safeAiMessage(mixed $message, array $recommendations, string $productType, float $budget): string
{
    $fallback = $recommendations === []
        ? 'No suitable ' . ($productType === 'suit' ? 'suits' : 'dresses')
            . ' matched your preferences within $' . number_format($budget, 2) . ' USD.'
        : 'Here are the products that best match your preferences.';

    if (!is_string($message)) {
        return $fallback;
    }

    $message = strip_tags($message);
    $message = preg_replace('/\s+/u', ' ', $message) ?? '';
    $message = trim($message);

    if ($message === '') {
        return $fallback;
    }

    if (mb_strlen($message) > 300) {
        $message = rtrim(mb_substr($message, 0, 297)) . '...';
    }

    return $message;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method !== 'POST') {
    header('Allow: POST');
    Response::json(405, [
        'success' => false,
        'message' => 'Method not allowed',
    ]);
}

try {
    $body = getJsonBody();
    $preferences = validatePreferences($body);

    $database = new Database();
    $pdo = $database->connect();
    $model = new Ai_recommendation($pdo);

    $products = $model->getMatchingProducts(
        $preferences['product_type'],
        $preferences['budget'],
        20
    );

    $preferenceSummary = [
        'product_type' => $preferences['product_type'],
        'height_cm' => $preferences['height'],
        'width_cm' => $preferences['width'],
        'maximum_budget' => $preferences['budget'],
        'currency' => 'USD',
    ];

    if ($products === []) {
        Response::json(200, [
            'success' => true,
            'message' => 'No ' . ($preferences['product_type'] === 'suit' ? 'men\'s suits' : 'dresses')
                . ' are currently available within your maximum budget of '
                . '$' . number_format($preferences['budget'], 2) . ' USD.',
            'recommendations' => [],
            'fit_note' => 'Try increasing your maximum budget or choosing the other product type. '
                . 'Only in-stock products within your budget are considered.',
            'preferences' => $preferenceSummary,
        ]);
    }

    $aiText = callGemini(geminiRequestBody($preferences, $products));
    $aiResponse = decodeAiResponse($aiText);
    $recommendations = verifiedRecommendations(
        $aiResponse,
        $products,
        $preferences['budget'],
        $preferences['product_type']
    );

    $message = safeAiMessage(
        $aiResponse['message'] ?? null,
        $recommendations,
        $preferences['product_type'],
        $preferences['budget']
    );

    if ($recommendations === [] && !empty($aiResponse['success'])) {
        $message = safeAiMessage(null, [], $preferences['product_type'], $preferences['budget']);
    }

    Response::json(200, [
        'success' => true,
        'message' => $message,
        'recommendations' => $recommendations,
        'fit_note' => fitNote($products),
        'preferences' => $preferenceSummary,
    ]);

} catch (AiServiceException $e) {
    error_log('AI recommendation error: ' . $e->getMessage());

    Response::json($e->status, [
        'success' => false,
        'message' => $e->getMessage(),
    ]);

} catch (PDOException $e) {
    error_log($e->getMessage());

    Response::json(500, [
        'success' => false,
        'message' => 'A database error occurred',
    ]);

} catch (Throwable $e) {
    error_log($e->getMessage());

    Response::json(500, [
        'success' => false,
        'message' => 'An internal server error occurred',
    ]);
}
