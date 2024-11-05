<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../config/db.php';
    include_once '../../model/donHang.php';
    include_once '../../model/chiTietDonHang.php';
    include_once '../../model/gioHang.php';
    include_once '../../middleware/check_role.php';

    if (checkRole('user')) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $customer_id = getCustomerIdFromToken();
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($customer_id) || empty($data['shipping_address'])) {
                http_response_code(400);
                echo json_encode(["message" => "The customer ID or shipping address is missing."]);
                exit();
            }
    
            $db = new db();
            $connect = $db->connect();
    
            $order = new DonHang($connect);
            $orderDetail = new ChiTietDonHang($connect);
            $cart = new GioHang($connect);
    
            // Setting up order details
            $order->customer_id = $customer_id;
            $order->status = "Chưa giải quyết";
            $order->shipping_address = $data['shipping_address'];
    
            // Fetch cart items for the customer
            $cart->customer_id = $customer_id;
            $cartItems = $cart->getCartByCustomer();
            $items = $cartItems['records'] ?? [];
    
            if (empty($cartItems)) {
                http_response_code(400);
                echo json_encode(["message" => "Cart is empty."]);
                exit();
            }
    
            // Calculate total amount and start transaction
            $connect->beginTransaction();
            $order->total_amount = array_reduce($cartItems, function($sum, $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                return $sum + $itemTotal;
            }, 0);


            if ($order->createOrder()) {
                foreach ($cartItems as $item) {
                    $orderDetail->order_id = $order->order_id;
                    $orderDetail->product_id = $item['product_id'];
                    $orderDetail->quantity = $item['quantity'];
                    $orderDetail->unit_price = $item['price'];
    
                    if (!$orderDetail->createOrderDetail()) {
                        $connect->rollBack();
                        http_response_code(503);
                        echo json_encode(["message" => "Unable to create order details."]);
                        exit();
                    }
                }
    
                // Commit transaction if all inserts were successful
                $connect->commit();
                // Clear cart by id user
                if(!$cart->clearCart())
                {
                    http_response_code(500);
                    echo json_encode(["message" => "Failed to clear cart."]);
                    exit();
                }
                http_response_code(201);
                echo json_encode(["message" => "Order created successfully.", "order_id" => $order->order_id]);
            } else {
                $connect->rollBack();
                http_response_code(503);
                echo json_encode(["message" => "Unable to create order."]);
            }
        }
    }

?>