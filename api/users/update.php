<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Method,Content-Type,Access-Control-Allow-Origin,Authorization,X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & Connect
    $database = new Database();
    $db = $database->connect();

    // Instatiate User object
    $user = new User($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $user->id = $_GET['id'];
    $user->name = $data->name;
    $user->surname = $data->surname;
    $user->email = $data->email;
    $user->password = $data->password;
    $user->department = $data->department;

    $result = $user->update();

    // Row count
    $num = $result->rowCount();

    // Check if update successful
    if ($num > 0 && $result) {
        echo json_encode(
            array(
                'status' => 'success',
                'message' => 'User updated'
            )
        );
    } else {
        echo json_encode(
            array(
                'status' => 'failed',
                'message' => 'Could not update the user'
            )
        );
    }