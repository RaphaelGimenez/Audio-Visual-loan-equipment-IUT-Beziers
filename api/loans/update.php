<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Method,Content-Type,Access-Control-Allow-Origin,Authorization,X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Loan.php';

    // Instantiate DB & Connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Loan Object
    $loan = new Loan($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $loan->id = $_GET['id'];
    $loan->state = $data->state;

    $result = $loan->update();

    // Row Count
    $num = $result->rowCount();

    // Check if update successful
    if ($num > 0 && $result) {
        echo json_encode(
            array(
                'status' => 'success',
                'message' => 'Loan updated'
            )
        );
    } else {
        echo json_encode(
            array(
                'status' => 'failed',
                'message' => 'Could not update the loan'
            )
        );
    }