<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Method,Content-Type,Access-Control-Allow-Origin,Authorization,X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & Connect
    $database = new Database();
    $db = $database->connect();

    // Instatiate User Object
    $user = new User($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $user->name = $data->name;
    $user->surname = $data->surname;
    $user->email = $data->email;
    $user->password = $data->password;
    $user->department = $data->department;

    // Register User
    if ($user->register()) {
        echo json_encode(
            array(
                'status' => 'success',
                'message' => 'User registered'
            )
        );
    } else {
        echo json_encode(
            array(
                'status' => 'failed',
                'message' => 'Could not register the user'
            )
        );
    }