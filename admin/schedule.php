<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <title>Schedule</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
        /* ✅ Restore native date/time picker appearance */
     input[type="date"].input-text,
    input[type="time"].input-text {
        -webkit-appearance: auto !important;
        appearance: auto !important;
        background-color: #fff !important;
        color: #000 !important;
        cursor: pointer;
    }

    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="time"]::-webkit-calendar-picker-indicator {
        opacity: 1 !important;
        display: block !important;
        cursor: pointer;
        filter: invert(0); /* ensures icon is visible if theme is dark */
    }
    </style>
</head>
<body>
<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION["usertype"] != 'a') {
    header("location: ../login.php");
    exit();
}

include("../connection.php");
?>
<div class="container">
    <div class="menu">
        <table class="menu-container" border="0">
            <tr>
                <td style="padding:10px" colspan="2">
                    <table border="0" class="profile-container">
                        <tr>
                            <td width="30%" style="padding-left:20px">
                                <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                            </td>
                            <td style="padding:0px;margin:0px;">
                                <p class="profile-title">Administrator</p>
                                <p class="profile-subtitle">admin@edoc.com</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="../logout.php">
                                    <input type="button" value="Log out" class="logout-btn btn-primary-soft btn">
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="menu-row">
                <td class="menu-btn menu-icon-dashbord">
                    <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-doctor">
                    <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">Doctors</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-schedule menu-active menu-icon-schedule-active">
                    <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Schedule</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-appoinment">
                    <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">Appointment</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-patient">
                    <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">Patients</p></div></a>
                </td>
            </tr>
        </table>
    </div>

    <div class="dash-body">
        <table border="0" width="100%" style="margin-top:25px;">
            <tr>
                <td width="13%">
                    <a href="schedule.php">
                        <button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">Back</button>
                    </a>
                </td>
                <td>
                    <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Schedule Manager</p>
                </td>
                <td width="15%">
                    <p style="font-size: 14px;color: rgb(119, 119, 119);text-align: right;">Today's Date</p>
                    <p class="heading-sub12" style="margin: 0;text-align: right;">
                        <?php 
                        date_default_timezone_set('Asia/Kolkata');
                        $today = date('Y-m-d');
                        echo $today;
                        $list110 = $database->query("select * from schedule;");
                        ?>
                    </p>
                </td>
                <td width="10%">
                    <button class="btn-label"><img src="../img/calendar.svg" width="100%"></button>
                </td>
            </tr>

            <tr>
                <td colspan="4">
                    <div style="display: flex;margin-top: 40px;">
                        <div class="heading-main12" style="margin-left: 45px;font-size:20px;">Schedule a Session</div>
                        <a href="?action=add-session&id=none&error=0" class="non-style-link">
                            <button class="login-btn btn-primary btn button-icon" style="margin-left:25px;background-image: url('../img/icons/add.svg');">Add a Session</button>
                        </a>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="4" style="padding-top:10px;width: 100%;">
                    <p class="heading-main12" style="margin-left: 45px;font-size:18px;">All Sessions (<?php echo $list110->num_rows; ?>)</p>
                </td>
            </tr>

            <?php
            // Display all sessions
            echo '<tr><td colspan="4">';
            echo '<div class="abc scroll">';
            echo '<table width="95%" class="sub-table scrolldown" border="0">';
            echo '<thead><tr>';
            echo '<th class="table-headin">Session Title</th>';
            echo '<th class="table-headin">Doctor</th>';
            echo '<th class="table-headin">Scheduled Date</th>';
            echo '<th class="table-headin">Scheduled Time</th>';
            echo '<th class="table-headin">Max Patients</th>';
            echo '<th class="table-headin">Events</th>';
            echo '</tr></thead><tbody>';
            if ($list110->num_rows == 0) {
                echo '<tr><td colspan="6" style="text-align:center;">No sessions found.</td></tr>';
            } else {
                while ($row = $list110->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                    // Get doctor name
                    $docid = $row['docid'];
                    $docres = $database->query("SELECT docname FROM doctor WHERE docid='$docid'");
                    $docname = $docres && $docres->num_rows > 0 ? $docres->fetch_assoc()['docname'] : 'Unknown';
                    echo '<td>' . htmlspecialchars($docname) . '</td>';
                    echo '<td>' . htmlspecialchars($row['scheduledate']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['scheduletime']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nop']) . '</td>';
                    echo '<td>';
                    echo '<a href="?action=view&id=' . $row['scheduleid'] . '" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-view">View</button></a> ';
                    echo '<a href="?action=drop&id=' . $row['scheduleid'] . '&name=' . urlencode($row['title']) . '" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-delete">Remove</button></a>';
                    echo '</td>';
                    echo '</tr>';
                }
            }
            echo '</tbody></table></div></td></tr>';
            ?>

        </table>
    </div>
</div>

<?php
if ($_GET) {
    $id = $_GET["id"];
    $action = $_GET["action"];

    // ✨ MODIFIED ADD-SESSION POPUP ✨
    if ($action == 'add-session') {
        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <a class="close" href="schedule.php">&times;</a> 
                    <div style="display: flex;justify-content: center;">
                        <div class="abc">
                            <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                <tr><td><p style="font-size: 25px;font-weight: 500;">Add New Session</p><br></td></tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <form action="add-session.php" method="POST" class="add-new-form">
                                            <label for="title" class="form-label">Session Title : </label>
                                            <input type="text" name="title" class="input-text" placeholder="Name of this Session" required><br>

                                            <label for="docid" class="form-label">Select Doctor: </label>
                                            <select name="docid" class="box" required>
                                                <option value="" disabled selected hidden>Choose Doctor</option>';
                                                
                                                $list11 = $database->query("select * from doctor order by docname asc;");
                                                while ($row00 = $list11->fetch_assoc()) {
                                                    echo "<option value='{$row00['docid']}'>{$row00['docname']}</option>";
                                                }

                                echo '</select><br><br>

                                            <label for="nop" class="form-label">Number of Patients per Session:</label>
                                            <input type="number" name="nop" class="input-text" min="1" required><br>

                                            <label class="form-label">Session Dates & Times:</label>
                                            <div id="timeslot-container">
                                                <div class="timeslot">
                                                    <input type="date" name="date[]" class="input-text" min="'.date('Y-m-d').'" required>
                                                    <select name="time[]" class="input-text" required>
                                                        <option value="" disabled selected hidden>Choose Time</option>
                                                        <option value="09:00">09:00</option>
                                                        <option value="09:30">09:30</option>
                                                        <option value="10:00">10:00</option>
                                                        <option value="10:30">10:30</option>
                                                        <option value="11:00">11:00</option>
                                                        <option value="11:30">11:30</option>
                                                        <option value="12:00">12:00</option>
                                                        <option value="12:30">12:30</option>
                                                        <option value="13:00">13:00</option>
                                                        <option value="13:30">13:30</option>
                                                        <option value="14:00">14:00</option>
                                                        <option value="14:30">14:30</option>
                                                        <option value="15:00">15:00</option>
                                                        <option value="15:30">15:30</option>
                                                        <option value="16:00">16:00</option>
                                                        <option value="16:30">16:30</option>
                                                        <option value="17:00">17:00</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="button" onclick="addTimeslot()" class="login-btn btn-primary-soft btn" style="margin-top:10px;">+ Add Another Timeslot</button>

                                            <br><br>
                                            <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">
                                            &nbsp;&nbsp;
                                            <input type="submit" value="Place this Session" class="login-btn btn-primary btn" name="shedulesubmit">
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </center>
            </div>
        </div>
 
        <script>
        // This lets you add multiple session timeslots dynamically in the form, and remove them if needed.
        function addTimeslot() {
            const container = document.getElementById("timeslot-container");
            const div = document.createElement("div");
            div.className = "timeslot";
            div.innerHTML = `
                <input type="date" name="date[]" class="input-text" min="${new Date().toISOString().split("T")[0]}" required>
                <input type="time" name="time[]" class="input-text" required>
                <button type="button" onclick="this.parentElement.remove()" class="login-btn btn-primary-soft btn">Remove</button>
            `;
            container.appendChild(div);
        }
        </script>
        ';
    }
//Delete Session Popup
    if ($action == 'drop') {
        $nameget = $_GET["name"]; //Session Title
        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <h2>Are you sure?</h2>
                    <a class="close" href="schedule.php">&times;</a>
                    <div class="content">
                        You want to delete this record<br>(' . substr($nameget, 0, 40) . ').
                    </div>
                    <div style="display: flex;justify-content: center;">
                        <a href="delete-session.php?id=' . $id . '" class="non-style-link">
                            <button class="btn-primary btn" style="margin:10px;padding:10px;">Yes</button>
                        </a>
                        &nbsp;&nbsp;&nbsp;
                        <a href="schedule.php" class="non-style-link">
                            <button class="btn-primary btn" style="margin:10px;padding:10px;">No</button>
                        </a>
                    </div>
                </center>
            </div>
        </div>
        ';
    }

    // Keep your other popups (session-added, view) unchanged...
}
?>
</body>
</html>
