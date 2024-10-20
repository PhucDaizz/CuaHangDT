<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once('../../config/db.php');
    include_once('../../model/nhaCungCap.php');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new db();
        $connect = $db->connect();
    
        $nhaCungCap = new NhaCungCap($connect);
        $read = $nhaCungCap->read();
    
        $num = $read->rowCount();
    
        if($num > 0){
            $nhaCungCap_array = [];
            $nhaCungCap_array['data'] = [];
    
            while($row = $read->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $danhMucSP_item = array(
                    'supplier_id' => $supplier_id,
                    'supplier_name' => $supplier_name,
                    'contact_email' => $contact_email,
                    'phone_number' => $phone_number,
                    'address' => $address
                );
                array_push($nhaCungCap_array['data'],$danhMucSP_item);
            }
            echo json_encode($nhaCungCap_array);
        }
    } else{
        echo json_encode(array('message' => 'Invalid request method. Only GET requests are allowed.'));
    }

?>
