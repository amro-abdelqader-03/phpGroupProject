<?php
class OrderModel {
    private static function estimatedDeliveryDate(): string
    {
        return (new DateTimeImmutable('today'))->modify('+5 days')->format('Y-m-d');
    }

    public static function createStripeOrder(PDO $pdo, int $userId, array $data): array
    {
        $pdo->beginTransaction();

        try {
            $cartQuery = "SELECT c.id AS cart_id, ci.product_id, ci.variant_id, ci.quantity,
                                 p.product_name, p.price, p.stock_quantity AS product_stock,
                                 s.category_id, pv.size AS variant_size, pv.stock_quantity AS variant_stock
                          FROM carts c
                          JOIN cart_items ci ON ci.cart_id = c.id
                          JOIN products p ON p.id = ci.product_id
                          JOIN subcategories s ON s.id = p.subcategory_id
                          LEFT JOIN product_variants pv ON pv.id = ci.variant_id AND pv.product_id = p.id
                          WHERE c.user_id = :user_id
                          ORDER BY ci.id
                          FOR UPDATE";
            $cartStmt = $pdo->prepare($cartQuery);
            $cartStmt->execute([':user_id' => $userId]);
            $items = $cartStmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$items) {
                throw new DomainException('Your bag is empty. Add an item before checkout.');
            }

            $subtotal = 0.0;
            foreach ($items as $item) {
                $quantity = filter_var($item['quantity'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 99]]);
                if ($quantity === false) {
                    throw new DomainException('An item in your bag has an invalid quantity.');
                }

                if (in_array((int) $item['category_id'], [1, 3, 4, 5, 7], true) && empty($item['variant_size'])) {
                    throw new DomainException('Choose a size for every clothing item before checkout.');
                }

                $availableStock = $item['variant_id'] === null ? $item['product_stock'] : $item['variant_stock'];
                if ($availableStock === null || (int) $availableStock < $quantity) {
                    throw new DomainException('One or more items are no longer available in the requested quantity.');
                }

                $subtotal += (float) $item['price'] * $quantity;
            }

            $shippingCost = 5.00;
            $total = round($subtotal + $shippingCost, 2);
            $revenue = round($subtotal, 2);
            $estimatedDelivery = self::estimatedDeliveryDate();
            $shippingAddress = 'Street: ' . $data['street'] . ', Building: ' . $data['building'] . ', Apartment: ' . $data['apartment'];

            $orderStmt = $pdo->prepare(
                "INSERT INTO orders (user_id, estimated_delivery, total_amount, order_status, full_name, phone, country, city, area,
                                    shipping_address, building_details, shipping_cost, revenue)
                 VALUES (:user_id, :estimated_delivery, :total, 'Pending', :name, :phone, 'Jordan', :city, :area, :address, :instructions, :shipping, :revenue)"
            );
            $orderStmt->execute([
                ':user_id' => $userId,
                ':estimated_delivery' => $estimatedDelivery,
                ':total' => $total,
                ':name' => $data['name'],
                ':phone' => $data['phone'],
                ':city' => $data['city'],
                ':area' => $data['area'],
                ':address' => $shippingAddress,
                ':instructions' => $data['instructions'],
                ':shipping' => $shippingCost,
                ':revenue' => $revenue,
            ]);
            $orderId = (int) $pdo->lastInsertId();

            $itemStmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, variant_id, quantity, unit_price) VALUES (?, ?, ?, ?, ?)'
            );
            foreach ($items as $item) {
                $itemStmt->execute([$orderId, $item['product_id'], $item['variant_id'], $item['quantity'], $item['price']]);
            }

            $paymentStmt = $pdo->prepare(
                "INSERT INTO payments (order_id, payment_method, amount, payment_status) VALUES (?, 'Credit Card', ?, 'Pending')"
            );
            $paymentStmt->execute([$orderId, $total]);

            $pdo->commit();
            return ['order_id' => $orderId, 'items' => $items, 'subtotal' => $subtotal, 'shipping' => $shippingCost, 'total' => $total, 'estimated_delivery' => $estimatedDelivery];
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function saveStripeSession(PDO $pdo, int $orderId, string $sessionId): void
    {
        $stmt = $pdo->prepare('UPDATE payments SET transaction_reference = ? WHERE order_id = ? AND payment_status = \'Pending\'');
        $stmt->execute([$sessionId, $orderId]);
        if ($stmt->rowCount() !== 1) {
            throw new RuntimeException('Unable to associate the payment session with the order.');
        }
    }

    public static function getOrderForCustomer(PDO $pdo, int $orderId, int $userId): ?array
    {
        $orderStmt = $pdo->prepare(
            'SELECT o.id, o.order_date, o.estimated_delivery, o.total_amount, o.order_status, o.full_name, o.phone,
                    o.city, o.area, o.shipping_address, o.building_details, p.payment_status, p.amount
             FROM orders o JOIN payments p ON p.order_id = o.id
             WHERE o.id = ? AND o.user_id = ?'
        );
        $orderStmt->execute([$orderId, $userId]);
        $order = $orderStmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) {
            return null;
        }

        $itemsStmt = $pdo->prepare(
            'SELECT oi.product_id, p.product_name, oi.quantity, oi.unit_price, oi.variant_id
             FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ? ORDER BY oi.id'
        );
        $itemsStmt->execute([$orderId]);
        $order['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
        return $order;
    }

    public static function completeStripePayment(PDO $pdo, int $orderId, int $userId, string $sessionId, int $paidAmount, string $currency): ?array
    {
        $pdo->beginTransaction();

        try {
            $orderStmt = $pdo->prepare(
                "SELECT o.id, o.order_date, o.estimated_delivery, o.total_amount, o.order_status, o.full_name, o.phone,
                        o.city, o.area, o.shipping_address, o.building_details, p.payment_status, p.amount
                 FROM orders o
                 JOIN payments p ON p.order_id = o.id
                 WHERE o.id = ? AND o.user_id = ? AND p.transaction_reference = ?
                 FOR UPDATE"
            );
            $orderStmt->execute([$orderId, $userId, $sessionId]);
            $order = $orderStmt->fetch(PDO::FETCH_ASSOC);
            if (!$order) {
                $pdo->rollBack();
                return null;
            }
            if (strtolower($currency) !== 'usd' || $paidAmount !== (int) round((float) $order['amount'] * 100)) {
                throw new RuntimeException('The confirmed payment amount does not match the order total.');
            }

            if ($order['payment_status'] !== 'Paid') {
                $paymentStmt = $pdo->prepare(
                    "UPDATE payments SET payment_status = 'Paid', paid_at = NOW() WHERE order_id = ? AND transaction_reference = ? AND payment_status = 'Pending'"
                );
                $paymentStmt->execute([$orderId, $sessionId]);
                if ($paymentStmt->rowCount() !== 1) {
                    throw new RuntimeException('The payment record could not be updated.');
                }

                $statusStmt = $pdo->prepare("UPDATE orders SET order_status = 'Confirmed' WHERE id = ? AND user_id = ?");
                $statusStmt->execute([$orderId, $userId]);
                $order['payment_status'] = 'Paid';
                $order['order_status'] = 'Confirmed';
            }

            $itemsStmt = $pdo->prepare(
                'SELECT oi.product_id, p.product_name, oi.quantity, oi.unit_price, oi.variant_id
                 FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ? ORDER BY oi.id'
            );
            $itemsStmt->execute([$orderId]);
            $order['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

            $cartStmt = $pdo->prepare('SELECT id FROM carts WHERE user_id = ? ORDER BY id DESC LIMIT 1');
            $cartStmt->execute([$userId]);
            $cartId = $cartStmt->fetchColumn();
            if ($cartId) {
                $cartItemsStmt = $pdo->prepare('SELECT product_id, variant_id, quantity FROM cart_items WHERE cart_id = ? ORDER BY product_id, variant_id, quantity');
                $cartItemsStmt->execute([$cartId]);
                $cartItems = $cartItemsStmt->fetchAll(PDO::FETCH_ASSOC);
                $orderedItems = array_map(static fn(array $item): array => [
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                ], $order['items']);
                usort($orderedItems, static fn(array $a, array $b): int => [$a['product_id'], $a['variant_id'], $a['quantity']] <=> [$b['product_id'], $b['variant_id'], $b['quantity']]);
                if ($cartItems === $orderedItems) {
                    $clearStmt = $pdo->prepare('DELETE FROM cart_items WHERE cart_id = ?');
                    $clearStmt->execute([$cartId]);
                }
            }

            $pdo->commit();
            return $order;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function createOrderAndPayment($pdo, $userId, $data, $paymentMethod) {
        try {
            $pdo->beginTransaction();

            $cartQuery = "SELECT c.id as cart_id, ci.product_id, ci.variant_id, ci.quantity, p.price, p.cost_price,
                                 s.category_id, pv.size AS variant_size
                          FROM carts c 
                          JOIN cart_items ci ON c.id = ci.cart_id 
                          JOIN products p ON ci.product_id = p.id 
                          JOIN subcategories s ON s.id = p.subcategory_id
                          LEFT JOIN product_variants pv ON pv.id = ci.variant_id AND pv.product_id = p.id
                          WHERE c.user_id = :user_id";
            
            $cartStmt = $pdo->prepare($cartQuery);
            $cartStmt->execute([':user_id' => $userId]);
            $cartItems = $cartStmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($cartItems)) {
                throw new Exception("Cart is empty");
            }

            $productsTotal = 0.00;
            $revenue = 0.00;
            $shippingCost = 5.00; 
            $estimatedDelivery = self::estimatedDeliveryDate();

            foreach ($cartItems as $item) {
                if (in_array((int) $item['category_id'], [1, 3, 4, 5, 7], true) && empty($item['variant_size'])) {
                    throw new DomainException('Choose a size for every clothing item before checkout.');
                }

                $itemTotal = $item['price'] * $item['quantity'];
                $itemCostTotal = $item['cost_price'] * $item['quantity'];
                
                $productsTotal += $itemTotal;
                $revenue += ($itemTotal - $itemCostTotal);
            }

            $totalAmount = $productsTotal + $shippingCost;

            $shippingAddress = "Street: " . $data['street'] . ", Bldg: " . $data['building'] . ", Apt: " . $data['apartment'];
            $buildingDetails = $data['instructions'];

            $orderQuery = "INSERT INTO orders (user_id, order_date, estimated_delivery, total_amount, order_status, full_name, phone, country, city, area, shipping_address, building_details, shipping_cost, revenue)
                           VALUES (:user_id, NOW(), :estimated_delivery, :total_amount, 'Pending', :full_name, :phone, 'Jordan', :city, :area, :shipping_address, :building_details, :shipping_cost, :revenue)";
            
            $stmt = $pdo->prepare($orderQuery);
            $stmt->execute([
                ':user_id'            => $userId,
                ':estimated_delivery' => $estimatedDelivery,
                ':total_amount'     => $totalAmount,
                ':full_name'        => $data['name'],
                ':phone'            => $data['phone'],
                ':city'             => $data['city'],
                ':area'             => $data['area'],
                ':shipping_address' => $shippingAddress,
                ':building_details' => $buildingDetails,
                ':shipping_cost'    => $shippingCost,
                ':revenue'          => $revenue
            ]);

            $orderId = $pdo->lastInsertId();

            $orderItemQuery = "INSERT INTO order_items (order_id, product_id, variant_id, quantity, unit_price) 
                               VALUES (:order_id, :product_id, :variant_id, :quantity, :unit_price)";
            $orderItemStmt = $pdo->prepare($orderItemQuery);

            $dbPaymentMethod = ($paymentMethod === 'cash-on-delivery') ? 'Cash on Delivery' : 'Credit Card';

            foreach ($cartItems as $item) {
                $orderItemStmt->execute([
                    ':order_id'   => $orderId,
                    ':product_id' => $item['product_id'],
                    ':variant_id' => $item['variant_id'],
                    ':quantity'   => $item['quantity'],
                    ':unit_price' => $item['price']
                ]);
            }
            
            $paymentQuery = "INSERT INTO payments (order_id, payment_method, amount, payment_status, created_at) 
                             VALUES (:order_id, :payment_method, :amount, 'Pending', NOW())";
            
            $paymentStmt = $pdo->prepare($paymentQuery);
            $paymentStmt->execute([
                ':order_id'       => $orderId,
                ':payment_method' => $dbPaymentMethod,
                ':amount'         => $totalAmount
            ]);

            $getCartIdQuery = "SELECT id FROM carts WHERE user_id = :user_id";
            $getCartStmt = $pdo->prepare($getCartIdQuery);
            $getCartStmt->execute([':user_id' => $userId]);
            $cartData = $getCartStmt->fetch(PDO::FETCH_ASSOC);

            if ($cartData) {
                $cartId = $cartData['id'];
                $clearItemsQuery = "DELETE FROM cart_items WHERE cart_id = :cart_id";
                $clearItemsStmt = $pdo->prepare($clearItemsQuery);
                $clearItemsStmt->execute([':cart_id' => $cartId]);
            }

            $pdo->commit();
            return $orderId;

        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}