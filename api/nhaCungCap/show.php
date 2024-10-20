<?php

    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once('../../config/db.php');
    include_once('../../model/nhaCungCap.php');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new db();
        $connect = $db->connect();
    
        $nhaCungCap = new NhaCungCap($connect);
        $nhaCungCap->supplier_id = isset($_GET['id']) ? $_GET['id'] : die();
    
        $nhaCungCap->show();
    
        $nhaCungCap_item = array(
            'supplier_id' => $nhaCungCap->supplier_id,
            'supplier_name' => $nhaCungCap->supplier_name,
            'contact_email' => $nhaCungCap->contact_email,
            'phone_number' => $nhaCungCap->phone_number,
            'address' => $nhaCungCap->address
        );
        echo(json_encode($nhaCungCap_item));
    } else{
        echo json_encode(array('message' => 'Invalid request method. Only GET requests are allowed.'));
    }

?>