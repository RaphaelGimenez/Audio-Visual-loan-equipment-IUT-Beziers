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
    $equipment->id = isset($_GET['id']) ? $_GET['id'] : die();

    $result = $equipment->delete();

    // Row count
    $num = $result->rowCount();

    // Check if equipment removed
    if ($num > 0) {
        echo json_encode(
            array('status' => 'success', 'message' => 'Equipment deleted'), JSON_PRETTY_PRINT
        );
    } else {
        echo json_encode(
            array('success' => 'failed', 'message' => 'Couldn\'t delete the equipment'), JSON_PRETTY_PRINT
        );
    }