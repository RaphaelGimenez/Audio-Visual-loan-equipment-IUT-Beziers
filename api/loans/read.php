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

    // Check Params
    if (isset($_GET['id'])) {
        $loan->id = $_GET['id'];
        $result = $loan->read_single();
    } elseif (isset($_GET['idU'])) {
        $loan->idU = $_GET['idU'];
        $result = $loan->read_user();
    } else {
        $result = $loan->read();
    }

    // Row Count
    $num = $result->rowCount();

    // Check if any loan
    if ($num > 0) {
        // Loans arr
        $loans_arr = array();
        $loans_arr['status'] = 'success';
        $loans_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            
            extract($row);

            $loan_item = array (
                'id' => $id,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'project' => $project,
                'state' => $state,
                'user' => array(
                    'id' => $idU,
                    'name' => $name,
                    'surname' => $surname,
                    'email' => $email,
                    'department' => $department
                ),
                'eqList' => array()
            );

            // Get loan's equipment
                // Initialize loan's id
                $contain->idL = $id;

                $equipment_list = $contain->read_equipment();

                while ($row_equipment = $equipment_list->fetch(PDO::FETCH_ASSOC)) {
                    extract($row_equipment);

                    $equipment_item = array(
                        'id' => $id,
                        'name' => $name
                    );

                    // Push equipment to the list
                    array_push($loan_item['eqList'], $equipment_item);
                }

            array_push($loans_arr['data'], $loan_item);
        }
        
        // Turn to JSON & output
        echo json_encode($loans_arr, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(
            array(
                'status' => 'failed',
                'message' => 'Nothing found'
            )
        );
    }