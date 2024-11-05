
<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../../config/db.php';
    include_once '../../model/gioHang.php';
    include_once '../../middleware/check_role.php';

    
    if(checkRole('user')){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            
            $customer_id = getCustomerIdFromToken();

            $db = new db();
            $connect = $db->connect();
            $cart = new GioHang($connect);
        
            // $customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : die(json_encode(["message" => "Customer ID is required."]));
            $cart->customer_id = $customer_id;
        
            $result = $cart->getCartByCustomer();
            
        
            if (!empty($result)) {
                $cart_arr = array();
                $cart_arr["records"] = array();
        
                foreach ($result as $row) { 
                    $cart_item = array( 
                        "cart_id" => $row['cart_id'], 
                        "product_id" => $row['product_id'], 
                        "product_name" => $row['product_name'], 
                        "price" => $row['price'], 
                        "quantity" => $row['quantity'], 
                        "thumbnail" => $row['thumbnail_image'], 
                        "added_at" => $row['added_at'] 
                    ); 
                    array_push($cart_arr["records"], $cart_item);
                }
                http_response_code(200);
                echo json_encode($cart_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No items found in cart."));
            }
        }
    }

?>