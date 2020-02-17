<?php
require_once("../config/dbcontroller.php");
/*
A domain Class to demonstrate RESTful web services
*/
Class Ticket {
    private $tickets = array();
    public function getAllTickets(){
        if(isset($_GET['severity'])){
            $severity = $_GET['severity'];
            $query = 'SELECT * FROM ticket WHERE name LIKE "%' .$severity. '%"';
        } else {
            $query = 'SELECT * FROM ticket';
        }
        $dbcontroller = new DBController();
        $this->tickets = $dbcontroller->executeSelectQuery($query);
        return $this->tickets;
    }

    public function addTicket(){
        if(isset($_POST['severity']) &&  isset($_POST['problem_statement'])){
            $severity = $_POST['severity'];

            $problem_statement = $_POST['problem_statement'];

            $query = "insert into ticket (problem_statement,severity) values ('" . $problem_statement ."','". $severity ."')";
            $dbcontroller = new DBController();
            $result = $dbcontroller->executeQuery($query);
            if($result != 0){
                $result = array('success'=>1);
                return $result;
            }
        }
    }

    public function deleteTicket(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $query = 'DELETE FROM ticket WHERE id = '.$id;
            $dbcontroller = new DBController();
            $result = $dbcontroller->executeQuery($query);
            if($result != 0){
                $result = array('success'=>1);
                return $result;
            }
        }
    }

    public function editTicket(){
        if(isset($_POST['severity']) &&  isset($_POST['problem_statement']) && isset($_GET['id'])){
            $severity = $_POST['severity'];
            $problem_statement = $_POST['problem_statement'];

            $query = "UPDATE ticket SET problem_statement = '".$problem_statement."',severity ='". $severity ."' WHERE id = ".$_GET['id'];
        }
        $dbcontroller = new DBController();
        $result= $dbcontroller->executeQuery($query);
        if($result != 0){
            $result = array('success'=>1);
            return $result;
        }
    }

}
?>