<?php

    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once('../../config/db.php');
    include_once('../../model/sanPham.php');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new db();
        $connect = $db->connect();
    
        $sanPham = new SanPham($connect);
        $sanPham->product_id = isset($_GET['id']) ? $_GET['id'] : die();
    
        $sanPham->show();
    
        $sanPham_item = array(
            'product_id' => $sanPham->product_id,
            'product_name' => $sanPham->product_name,
            'description' => $sanPham->description,
            'price' => $sanPham->price,
            'stock' => $sanPham->stock,
            'category_id' => $sanPham->category_id,
            'supplier_id' => $sanPham->supplier_id,
            'created_at' => $sanPham->created_at,
            'updated_at' => $sanPham->updated_at,
        );
        echo(json_encode($sanPham_item));
    } else{
        echo json_encode(array('message' => 'Invalid request method. Only GET requests are allowed.'));
    }

?>