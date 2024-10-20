<?php

    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once('../../config/db.php');
    include_once('../../model/danhMucSP.php');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new db();
        $connect = $db->connect();
    
        $danhMucSP = new DanhMucSp($connect);
        $danhMucSP->category_id = isset($_GET['id']) ? $_GET['id'] : die();
    
        $danhMucSP->show();
    
        $danhMucSP_item = array(
            'category_id' => $danhMucSP->category_id,
            'category_name' => $danhMucSP->category_name,
            'description' => $danhMucSP->description
        );
        echo(json_encode($danhMucSP_item));
    } else{
        echo json_encode(array('message' => 'Invalid request method. Only GET requests are allowed.'));
    }

?>