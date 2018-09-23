<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Method,Content-Type,Access-Control-Allow-Origin,Authorization,X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Equipment.php';

    // Instantiate DB & Connect
    $database = new Database();
    $db = $database->connect();

    // Instatiate Equipment Object
    $equipment = new Equipment($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
    
    $equipment->name = $data->name;
    $equipment->description = $data->description;
    $equipment->category = $data->category;
    $equipment->image = $data->image;

    // Create Equipment
    if ($equipment->create()) {
        echo json_encode(
            array(
                'status' => 'success',
                'message' => 'Equipment added to database'
            )
        );
    } else {
        echo json_encode(
            array(
                'status' => 'failed',
                'message' => 'Could not add the equipment to database'
            )
        );
    }