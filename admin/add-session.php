<?php
session_start();
include("../connection.php");

if (isset($_POST["shedulesubmit"])) {
    $title = $_POST['title'];
    $docid = $_POST['docid'];
    $nop = $_POST['nop'];
    $dates = $_POST['date'];
    $times = $_POST['time'];

    $inserted = 0;

    for ($i = 0; $i < count($dates); $i++) {
        $date = $dates[$i];
        $time = $times[$i];

        // Prevent duplicate session for same doctor/date/time
        $check = $database->query("SELECT * FROM schedule 
                                   WHERE docid='$docid' 
                                   AND scheduledate='$date' 
                                   AND scheduletime='$time'");
        if ($check->num_rows == 0) {
            $database->query("INSERT INTO schedule (title, docid, scheduledate, scheduletime, nop)
                              VALUES ('$title', '$docid', '$date', '$time', '$nop')");
            $inserted++;
        }
    }

    header("Location: schedule.php?action=session-added&title=" . urlencode($title));
    exit();
} else {
    header("Location: schedule.php");
    exit();
}
?>
