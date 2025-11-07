<?php

    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }
    

    //import database
    include("../connection.php");
    $sqlmain= "select * from patient where pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s",$useremail);
    $stmt->execute();
    $userrow = $stmt->get_result();
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];


    if($_POST){
        if(isset($_POST["booknow"])){
            $scheduleid=$_POST["scheduleid"];
            $date=$_POST["date"];
            // Check if slot is already booked
            $check = $database->query("SELECT * FROM appointment WHERE scheduleid=$scheduleid");
            if ($check->num_rows > 0) {
                // Slot already booked, redirect to appointment page
                header("location: appointment.php?action=slot-booked");
                exit();
                //This block books the appointment, gets its unique reference number, and redirects the user to a confirmation page.
            } else {
                $sql2="insert into appointment(pid,scheduleid,appodate) values ($userid,$scheduleid,'$date')";
                $result= $database->query($sql2);
                $ref_number = $database->insert_id;
                header("location: appointment.php?action=booking-added&id=".$ref_number."&titleget=none");
                exit();
            }
        }
    }
 ?>