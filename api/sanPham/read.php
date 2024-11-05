<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once('../../config/db.php');
    include_once('../../model/sanPham.php');


    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new db();
        $connect = $db->connect();
    
        $sanPham = new SanPham($connect);
    
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'product_id';
        $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'DESC';
    
        $read = $sanPham->read($page, $limit, $sort_by, $sort_order);
    
        $num = $read->rowCount();
    
        if($num > 0) {
            $sanPham_array = [];
            $sanPham_array['data'] = [];
            $sanPham_array['paging'] = [];
    
            while($row = $read->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $sanPham_item = array(
                    'product_id' =>  $product_id,
                    'product_name' =>  $product_name,
                    'description' =>  $description,
                    'price' =>  $price,
                    'stock' =>  $stock,
                    'category_id' =>  $category_id,
                    'supplier_id' =>  $supplier_id,
                    'created_at' =>  $created_at,
                    'updated_at' =>  $updated_at,
                    'thumbnail_image' => $thumbnail_image
                );
                array_push($sanPham_array['data'], $sanPham_item);
            }
    
            $total_records = $sanPham->getTotalCount();
            $total_pages = ceil($total_records / $limit);
    
            $sanPham_array['paging'] = array (
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_record' => $total_records,
                'record_per_page' => $limit,
                'sort_by' => $sort_by,
                'sort_order' => $sort_order
            );
            
            echo json_encode($sanPham_array);
    
        } else{ 
            echo json_encode(array('message' => 'Không tim thấy sản phẩm nào'));
        }

    } else{
        echo json_encode(array('message' => 'Invalid request method. Only GET requests are allowed.'));
    }

?>