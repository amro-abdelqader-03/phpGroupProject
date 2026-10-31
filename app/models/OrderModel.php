<?php
class OrderModel {
    public static function createOrderAndPayment($pdo, $userId, $data, $paymentMethod) {
        try {
            $pdo->beginTransaction();

            $cartQuery = "SELECT c.id as cart_id, ci.product_id, ci.variant_id, ci.quantity, p.price, p.cost_price 
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

            $productsTotal = 0.00;
            $revenue = 0.00;
            $shippingCost = 5.00; 

            foreach ($cartItems as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                $itemCostTotal = $item['cost_price'] * $item['quantity'];
                
                $productsTotal += $itemTotal;
                $revenue += ($itemTotal - $itemCostTotal);
            }

            // Total amount paid by customer includes products total plus shipping cost
            $totalAmount = $productsTotal + $shippingCost;

            $shippingAddress = "Street: " . $data['street'] . ", Bldg: " . $data['building'] . ", Apt: " . $data['apartment'];
            $buildingDetails = $data['instructions'];

            $orderQuery = "INSERT INTO orders (user_id, order_date, total_amount, order_status, full_name, phone, country, city, area, shipping_address, building_details, shipping_cost, revenue) 
                           VALUES (:user_id, NOW(), :total_amount, 'Pending', :full_name, :phone, 'Jordan', :city, :area, :shipping_address, :building_details, :shipping_cost, :revenue)";
            
            $stmt = $pdo->prepare($orderQuery);
            $stmt->execute([
                ':user_id'          => $userId,
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

            foreach ($cartItems as $item) {
                $orderItemStmt->execute([
                    ':order_id'   => $orderId,
                    ':product_id' => $item['product_id'],
                    ':variant_id' => $item['variant_id'],
                    ':quantity'   => $item['quantity'],
                    ':unit_price' => $item['price']
                ]);
            }

            $dbPaymentMethod = ($paymentMethod === 'cash-on-delivery') ? 'Cash on Delivery' : 'Credit Card';
            
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