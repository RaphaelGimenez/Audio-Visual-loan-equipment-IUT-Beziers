<?php
    // Headers
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Loan.php';
    include_once '../../models/Contain.php';

    // Instantiate DB & Connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Loan Object
    $loan = new Loan($db);
        //Instantiate Contain Object
        $contain = new Contain($db);

    if (isset($_GET['idE']) && isset($_GET['id'])) {
        // Check if any equipment's id given
        $contain->idE = $_GET['idE'];
        $contain->idL = $_GET['id'];
        $result = $contain->remove_equipment();
    } elseif (isset($_GET['id'])) {
        // Check if loan's id given
        $loan->id = $_GET['id'];
        $result = $loan->delete();
    } else {
        die();
    }

    // Row count
    $num = $result->rowCount();

    // Check if user removed
    if ($num > 0) {
        echo json_encode(
            array('status' => 'success', 'message' => 'Equipment removed from loan'), JSON_PRETTY_PRINT
        );
    } else {
        echo json_encode(
            array('success' => 'failed', 'message' => 'Couldn\'t remove the equipment from loan'), JSON_PRETTY_PRINT
        );
    }