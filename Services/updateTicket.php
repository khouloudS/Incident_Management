<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
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
    $ticket_id = $data->id;

    //GET ticket BY ID FROM DATABASE
    $get_ticket = "SELECT * FROM `ticket` WHERE id=:id";
    $get_stmt = $conn->prepare($get_ticket);
    $get_stmt->bindValue(':id', $ticket_id,PDO::PARAM_INT);
    $get_stmt->execute();


    //CHECK WHETHER THERE IS ANY ticket IN OUR DATABASE
    if($get_stmt->rowCount() > 0){

        // FETCH ticket FROM DATBASE
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);

        // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
        $ticket_problem_statement = isset($data->problem_statement) ? $data->problem_statement : $row['problem_statement'];
        $ticket_severity = isset($data->severity) ? $data->severity : $row['severity'];

        $update_query = "UPDATE `ticket` SET problem_statement = :problem_statement, severity = :severity WHERE id = :id";

        $update_stmt = $conn->prepare($update_query);

        // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
        $update_stmt->bindValue(':problem_statement', htmlspecialchars(strip_tags($ticket_problem_statement)),PDO::PARAM_STR);
        $update_stmt->bindValue(':severity', htmlspecialchars(strip_tags($ticket_severity)),PDO::PARAM_STR);
        $update_stmt->bindValue(':id', $ticket_id,PDO::PARAM_INT);


        if($update_stmt->execute()){
            header('HTTP/1.0 200 OK');
            $response=array(
                'status' => 200,
                'status_message' =>'Data updated successfully'
            );
        }else{
            header('HTTP/1.0 400 Bad Request');
            $response=array(
                'status' => 400,
                'status_message' =>'data not updated'
            );
        }

    }
    else{
        header('HTTP/1.0 404 Not found');
        $response=array(
            'status' => 404,
            'status_message' =>'Invlid ID'
        );
    }

    echo  json_encode($response);

}
?>