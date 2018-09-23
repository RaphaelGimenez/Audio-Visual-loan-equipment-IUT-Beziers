<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Method,Content-Type,Access-Control-Allow-Origin,Authorization,X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Loan.php';
    include_once '../../models/Contain.php';

    // Instantiate DB & Connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Loan Object
    $loan = new Loan($db);
        // Instantiate Contain object
        $contain = new Contain($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $loan->startDate = $data->startDate;
    $loan->endDate = $data->endDate;
    $loan->project = $data->project;
    $loan->state = $data->state;
    $loan->idU = $data->idU;
    $selectedEquipment = $data->selectedEquipment;

    // Get result (Should return last inserted id)
    $result = $loan->create();

    // Bind id to Contain's idL
    $contain->idL = $result;

    // Create Loan
    if ($result) {
        foreach ($selectedEquipment as $key => $val) {
            $contain->idE = $val;
            $contain->insert_equipment();
        }
        echo json_encode(
            array(
                'status' => 'success',
                'message' => 'Loan created'
            )
        );
    } else {
        echo json_encode(
            array(
                'status' => 'failed',
                'message' => 'Could not create the loan'
            )
        );
    }