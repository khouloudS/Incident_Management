<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
require '../config/dbConfig.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// CHECK GET ID PARAMETER OR NOT
if(isset($_GET['id']))
{
    //IF HAS ID PARAMETER
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT,[
        'options' => [
            'default' => 'all_tickets',
            'min_range' => 1
        ]
    ]);
}
else{
    $id = 'all_tickets';
}

// MAKE SQL QUERY
// IF GET TICKETS ID, THEN SHOW TICKETS BY ID OTHERWISE SHOW ALL TICKETS
$sql = is_numeric($id) ? "SELECT * FROM `ticket` WHERE id='$id'" : "SELECT * FROM `ticket`";

$stmt = $conn->prepare($sql);

$stmt->execute();

//CHECK WHETHER THERE IS ANY TICKETS IN OUR DATABASE
if($stmt->rowCount() > 0){
    // CREATE ticketS ARRAY
    $tickets_array = [];

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        $ticket_data = [
            'id' => $row['id'],
            'problem_statement' => $row['problem_statement'],
            'severity' => html_entity_decode($row['severity'])
        ];
        // PUSH TICKET DATA IN OUR $tickets_array ARRAY
        header('HTTP/1.0 200 OK');
        array_push($tickets_array, $ticket_data);

    }
    //SHOW TICKET/TICKET IN JSON FORMAT
    echo json_encode($tickets_array);


}
else{
    //IF THERE IS NO TICKETS IN OUR DATABASE
    header('HTTP/1.0 404 Not found');
    $response=array(
        'status' => 404,
        'status_message' =>'No ticket found'
    );
}
?>