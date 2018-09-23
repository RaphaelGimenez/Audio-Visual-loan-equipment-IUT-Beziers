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

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $user->email = $data->email;
    $user->password = $data->password;

    if ($user->login()) {
        echo json_encode(
            array(
                'status' => 'success',
                'data' => array(
                    'id' => $user->id,
                    'name' => $user->name,
                    'surname' => $user->surname
                ),
                'message' => 'User logged in'
            ),
            JSON_PRETTY_PRINT
        );
    } else {
        echo json_encode(
            array(
                'status' => 'failed',
                'message' => 'Email or password incorrect'
            ),
            JSON_PRETTY_PRINT
        );
    }

