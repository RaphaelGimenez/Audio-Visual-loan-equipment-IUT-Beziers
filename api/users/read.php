<?php
    // Headers
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & Connect
    $database = new Database();
    $db = $database->connect();

    // Instatiate User Object
    $user = new User($db);
    
    // Check Params
    if (isset($_GET['id'])) {
        $user->id = $_GET['id'];
        $result = $user->read_single();
    } else {
        $result = $user->read();
    }

    // Row Count
    $num = $result->rowCount();
    
    // Check if any user
    if ($num > 0) {
        // Users arr
        $users_arr = array();
        $users_arr['status'] = 'success';
        $users_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $user_item = array(
                'id' => $id,
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'department' => $department
            );

            // Push data
            array_push($users_arr['data'], $user_item);
        }

        // Turn to JSON & output
        echo json_encode($users_arr, JSON_PRETTY_PRINT);

    } else {
        // No users
        echo json_encode(
            array('message' => 'No users found'),
            JSON_PRETTY_PRINT
        );
    }