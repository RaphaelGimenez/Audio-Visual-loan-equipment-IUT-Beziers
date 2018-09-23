<?php
    // Headers
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Equipment.php';

    // Instantiate DB & Connect
    $database = new Database();
    $db = $database->connect();

    // Instatiate User object
    $equipment = new Equipment($db);

    // Check Params
    if (isset($_GET['id'])) {
        $equipment->id = $_GET['id'];
        $result = $equipment->read_single();
    } elseif (isset($_GET['category'])) {
        $equipment->category = $_GET['category'];
        $result = $equipment->read_category();
    } else {
        $result = $equipment->read();
    }

    // Row Count
    $num = $result->rowCount();

    // Chech if any equipment
    if ($num > 0) { 
        // Equipment arr
        $equipment_arr = array();
        $equipment_arr['status'] = 'success';
        $equipment_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $equipment_item = array(
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'category' => $category,
                'image' => $image
            );

            // Push data
            array_push($equipment_arr['data'], $equipment_item);
        }

        echo json_encode($equipment_arr);
    } else {
        echo json_encode(
            array(
                'status' => 'failed',
                'message' => 'No equipment found'
            )
        );
    }
    