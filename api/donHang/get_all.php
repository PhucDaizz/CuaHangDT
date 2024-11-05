<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once('../../config/db.php');
    include_once('../../model/donHang.php');
    include_once '../../middleware/check_role.php';

    if(checkRole('user')){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $customer_id = getCustomerIdFromToken();

            $db = new db();
            $connect = $db->connect();
            $order = new DonHang($connect);

            $order->customer_id = $customer_id;

            $result = $order->getAllOrderById();

            if (!empty($result)) {
                $cart_arr = array();
                $cart_arr["records"] = array();

                foreach ($result as $row) { 
                    $cart_item = array( 
                        "order_date" => $row['order_date'], 
                        "total_amount" => $row['total_amount'], 
                        "status" => $row['status'], 
                        "shipping_address" => $row['shipping_address']
                    ); 
                    array_push($cart_arr["records"], $cart_item);
                }
                http_response_code(200);
                echo json_encode($cart_arr);
            }
            else {
                http_response_code(404);
                echo json_encode(array("message" => "Order is null"));
            }
        }
    }

?>
