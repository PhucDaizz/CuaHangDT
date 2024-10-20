<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once('../../config/db.php');
    include_once('../../model/danhMucSP.php');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new db();
        $connect = $db->connect();
    
        $danhMucSP = new DanhMucSp($connect);
        $read = $danhMucSP->read();
    
        $num = $read->rowCount();
    
        if($num > 0){
            $danhMucSP_array = [];
            $danhMucSP_array['data'] = [];
    
            while($row = $read->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $danhMucSP_item = array(
                    'category_id' => $category_id,
                    'category_name' => $category_name,
                    'description' => $description
                );
                array_push($danhMucSP_array['data'],$danhMucSP_item);
            }
            echo json_encode($danhMucSP_array);
        }
    } else{
        echo json_encode(array('message' => 'Invalid request method. Only GET requests are allowed.'));
    }

?>
