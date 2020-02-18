<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require '../config/dbConfig.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));


//CHECKING, IF ID AVAILABLE ON $data
if(isset($data->id)){
    $msg['message'] = '';

    $id = $data->id;

    //GET ticket BY ID FROM DATABASE
    // YOU CAN REMOVE THIS QUERY AND PERFORM ONLY DELETE QUERY
    $check_ticket = "SELECT * FROM `ticket` WHERE id=:id";
    $check_ticket_stmt = $conn->prepare($check_ticket);
    $check_ticket_stmt->bindValue(':id', $id,PDO::PARAM_INT);
    $check_ticket_stmt->execute();

    //CHECK WHETHER THERE IS ANY ticket IN OUR DATABASE
    if($check_ticket_stmt->rowCount() > 0){

        //DELETE ticket BY ID FROM DATABASE
        $delete_ticket = "DELETE FROM `ticket` WHERE id=:id";
        $delete_ticket_stmt = $conn->prepare($delete_ticket);
        $delete_ticket_stmt->bindValue(':id', $id,PDO::PARAM_INT);

        if($delete_ticket_stmt->execute()){
            header('HTTP/1.0 200 OK');
            $response=array(
                'status' => 200,
                'status_message' =>'ticket Deleted Successfully'
            );
        }else{
            header('HTTP/1.0 400 Bad Request');
            $response=array(
                'status' => 400,
                'status_message' =>'data not Deleted'
            );
        }

    }else{
        header('HTTP/1.0 404 Not found');
        $response=array(
            'status' => 404,
            'status_message' =>'Invlid ID'
        );
    }
    // ECHO MESSAGE IN JSON FORMAT
    echo  json_encode($response);

}
?>