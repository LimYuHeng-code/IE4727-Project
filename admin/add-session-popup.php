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
                                        <option value="" disabled selected hidden>Choose Doctor</option>
                                        <?php
                                        $list11 = $database->query("select * from doctor order by docname asc;");
                                        while ($row00 = $list11->fetch_assoc()) {
                                            echo "<option value='{$row00['docid']}'>{$row00['docname']}</option>";
                                        }
                                        ?>
                                    </select><br><br>
<label class="form-label">Session Dates & Times:</label>
<div id="timeslot-container">
  <div class="timeslot">
    <input type="date" name="date[]" class="input-text" min="<?php echo date('Y-m-d'); ?>" required>
    <select name="time[]" class="input-text time-select" required></select>
  </div>
</div>

<button type="button" onclick="addTimeslot()" class="login-btn btn-primary-soft btn" style="margin-top:10px;">+ Add Another Timeslot</button>
<br><br>
<input type="reset" value="Reset" class="login-btn btn-primary-soft btn">
<input type="submit" value="Place this Session" class="login-btn btn-primary btn" name="shedulesubmit">

<script>
const timeOptionsHTML = (() => {
  let html = '<option value="" disabled selected hidden>Select Time</option>';
  for (let h = 0; h < 24; h++) {
    for (let m of ["00", "30"]) {
      const time = `${String(h).padStart(2,'0')}:${m}`;
      html += `<option value="${time}">${time}</option>`;
    }
  }
  return html;
})();

function fillAllSelects() {
  document.querySelectorAll('.time-select').forEach(select => {
    if (!select.options.length) select.innerHTML = timeOptionsHTML;
  });
}

function addTimeslot() {
  const container = document.getElementById("timeslot-container");
  const div = document.createElement("div");
  div.className = "timeslot";
  div.innerHTML = `
    <input type="date" name="date[]" class="input-text" min="${new Date().toISOString().split("T")[0]}" required>
    <select name="time[]" class="input-text time-select" required></select>
    <button type="button" onclick="this.parentElement.remove()" class="login-btn btn-primary-soft btn">Remove</button>
  `;
  container.appendChild(div);
  fillAllSelects();
}

document.addEventListener('DOMContentLoaded', fillAllSelects);
</script>
                                 </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </center>
        </div>
    </div>