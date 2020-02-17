<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require '../config/dbConfig.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';

// CHECK IF RECEIVED DATA FROM THE REQUEST
if(isset($data->problem_statement) && isset($data->severity)){
    // CHECK DATA VALUE IS EMPTY OR NOT
    if(!empty($data->problem_statement) && !empty($data->severity)){

        $insert_query = "INSERT INTO `ticket`(problem_statement,severity) VALUES(:problem_statement,:severity)";

        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':problem_statement', htmlspecialchars(strip_tags($data->problem_statement)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':severity', htmlspecialchars(strip_tags($data->severity)),PDO::PARAM_STR);

        if($insert_stmt->execute()){
            $msg['message'] = 'Data Inserted Successfully';
        }else{
            $msg['message'] = 'Data not Inserted';
        }

    }else{
        $msg['message'] = 'Oops! empty field detected. Please fill all the fields';
    }
}
else{
    $msg['message'] = 'Please fill all the fields | problem_statement, severity';
}
//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>