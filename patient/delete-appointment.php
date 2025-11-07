<?php

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
            header("location: ../login.php");
        }
    }
    
    if($_GET){
        //import database
        include("../connection.php");
        $id=$_GET["id"];
        $database->query("DELETE FROM appointment WHERE appoid='$id';");
        header("location: appointment.php");
    }


?>