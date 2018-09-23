<?php
    // Headers
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & Connect
    $database = new Database();
    $db = $database->connect();

    // Instatiate User object
    $user = new User($db);

    // Check Params
    $user->id = isset($_GET['id']) ? $_GET['id'] : die();

    $result = $user->delete();

    // Row count
    $num = $result->rowCount();

    // Check if user removed
    if ($num > 0) {
        echo json_encode(
            array('status' => 'success', 'message' => 'User removed from database'), JSON_PRETTY_PRINT
        );
    } else {
        echo json_encode(
            array('success' => 'failed', 'message' => 'Couldn\'t remove the user'), JSON_PRETTY_PRINT
        );
    }