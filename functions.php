<?php
  // SET DEFAULT TIME ZONE
  date_default_timezone_set('Europe/Dublin');
  // ESTABISH MYSQL SERVER CONNECTION SUBROUTINE
  function connect() {
    $conn = new mysqli("*", "*", "*", "*");
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
  }
  // MECHANISIM TO COMMUNICATE TO MYSQL SERVER
  function query($sql, $init, $data, $end) {
    $ret = '';
    $conn = connect();
    $result = $conn->query($sql);
    $rows = mysqli_num_rows($result);
    if ($rows > 0) {
      eval($init);
      while($row = $result->fetch_assoc()) {
        eval($data);
      }
    } else {
      err();
    }
    eval($end);
    $conn->close();
  }
  // DISPLAY ERROR WHERE SOMETHING GOES WRONG
  function err() {
    if (isset($_GET['t']) || $_GET['t'] == 'today') {
      printf("<p>There is no timetable for %s on %s</p>", isset($_GET['s']) ? $_GET['s'] : $_GET['q'], $_GET['t'] == 'today' ? jddayofweek(getDay('int'), 1) : $_GET['t']);
    } else {
      echo "Unknown error.";
    }
  }
  // DISPLAY OPTION TO REPORT ERROR
  function report() {
    return '<small><a href="index.php?p=contact&f=moderator">Report error.</a></small>';
  }
  // DISPLAY COURSES SELECTION BAR
  function courses() {
    $sql = "SELECT * FROM `courses_shortlist`";
    $start = "echo '<h3 class=\"center\">Courses</h3><table id=\"courses\" class=\"center\"><tr><th class=\"center\">ID</th></tr>';";
    $data = 'printf(\'<tr><td><a href="?q=%s">%s</a></td></tr>\', $row[\'id\'], strtoupper($row[\'id\']));';
    $end = "echo '</table>" . report() . "';";
    query($sql, $start, $data, $end);
  }
  // GET TITLE OF THE SELECTED COURSE
  function getTitle($code) {
    $sql = "SELECT title FROM `courses_shortlist` WHERE id='".$code."'";
    $data = 'printf("<h3 class=\"center\">%s</h3>", $row[\'title\']);';
    query($sql, "", $data, "");
  }
  // DISPLAY TIMETABLE SELECTED FROM COURSES SELECTION BAR
  function getTimetable($qa) {
    $con = connect();
    $q = mysqli_real_escape_string($con, $qa);
    $sql = 'SELECT * FROM `'.$q.'` '. where($con);
    $con->close();
    getTitle($q);
    popDropbox($q);
    $start = "echo '<tr class=\"header\"><th>Title</th><th>Day</th><th>Start</th><th>End</th><th>Location</th><th>Semester</th></tr>';";
    $data = 'if(date("G") >= substr($row[\'start\'], 0 , 2) && date("G") < substr($row[\'end\'], 0, 2) && getDay(\'int\') == $row[\'day\']){printf("<tr id=\"white\" id=\"%s\"><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td class=\"center\">%s</td></tr>", jddayofweek($row[\'day\'], 2), $row[\'title\'], jddayofweek($row[\'day\'], 2), $row[\'start\'], $row[\'end\'], $row[\'location\'], $row[\'sem\']);}else{printf("<tr id=\"%s\"><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td class=\"center\">%s</td></tr>", jddayofweek($row[\'day\'], 2), $row[\'title\'], jddayofweek($row[\'day\'], 2), $row[\'start\'], $row[\'end\'], $row[\'location\'], $row[\'sem\']);}';
    $end = "echo '</table>" . report() . "';";
    query($sql, $start, $data, $end);
  }
  // POPULATE FILTER COMBO BOX (MODULE, DAY)
  function popDropbox($q) {
    $start = 'echo \'<table id="timetable"><tr class="opt-row"><th><select class="form-control" onchange="window.location.href=this.value"><option>Select class...</option><option class="selector" value="timetables.php?'.$_SERVER['QUERY_STRING'].'&s=Group1">Group 1</option><option class="selector" value="timetables.php?'.$_SERVER['QUERY_STRING'].'&s=Group2">Group 2</option>\';';
    $data = 'printf(\'<option class="selector link" value="?%s&s=%s">%s</option>\', $_SERVER[\'QUERY_STRING\'], $row[\'title\'], $row[\'title\']);';
    query("SELECT DISTINCT `title` FROM `".$q."` ORDER BY title", $start, $data, "echo '</select></th>';");
    $start = 'echo \'<th><select id="day" class="form-control" onchange="window.location.href=this.value;"><option>Choose day...</option><option class="selector" value="timetables.php?'.$_SERVER['QUERY_STRING'].'&t=today">Today</option>\';';
    $data = 'printf(\'<option class="selector link" value="?%s&t=%s">%s</option>\', $_SERVER[\'QUERY_STRING\'], $row[\'day\'], jddayofweek($row[\'day\'], 2));';
    $end = 'echo \'</select></th><th><button class="btn btn-primary" onclick="window.location.href=this.value" value="timetables.php?q='.$q.'">Clear</button></th><th></th><th></th><th class="center" id="date">'.date("d/m/Y G:i").'</th></tr>\';';
    query("SELECT DISTINCT `day` FROM `".$q."` ORDER BY day", $start, $data, $end);
  }
  // ATTACH WHERE CLAUSE TO FILTER RESULTS FROM MYSQL SERVER
  function where($con) {
    if(isset($_GET['s']) && isset($_GET['t'])) {
      if($_GET['s'] == 'Group1') {
        if($_GET['t'] == 'today') {
          return "WHERE NOT (title LIKE '%2%') AND day=".getDay('int');
        }
        else {
          return "WHERE NOT (title LIKE '%2%') AND day=".mysqli_real_escape_string($con, $_GET['t']);
        }
      }
      elseif($_GET['s'] == 'Group2') {
        if($_GET['t'] == 'today') {
          return "WHERE NOT (title LIKE '%1%') AND day=".getDay('int');
        }
        else {
          return "WHERE NOT (title LIKE '%1%') AND day=".mysqli_real_escape_string($con, $_GET['t']);
        }
      }
      elseif($_GET['t'] == 'today') {
        return "WHERE title='".mysqli_real_escape_string($con, $_GET['s'])."' AND day=".getDay('int');
      } else {
        return "WHERE title='".mysqli_real_escape_string($con, $_GET['s'])."' AND day=".mysqli_real_escape_string($con, $_GET['t']);
      }
    }
    elseif (isset($_GET['s'])) {
      if($_GET['s'] == 'Group1') {
        return "WHERE NOT (title LIKE '%2%')";
      }
      elseif ($_GET['s'] == 'Group2') {
        return "WHERE NOT (title LIKE '%1%')";
      } else {
        return "WHERE title='".mysqli_real_escape_string($con, $_GET['s'])."'";
      }
    }
    elseif (isset($_GET['t'])) {
      if($_GET['t'] == 'today') {
          return "WHERE day=".getDay('int');
      }
      else {
        return "WHERE day=".mysqli_real_escape_string($con, $_GET['t']);
      }
    }
    else {
      return "";
    }
  }
  function change() {
    $sql = "SELECT * FROM `changelog` ORDER BY id ASC";
    $start = 'echo \'<table style="width: 100%">\';';
    $data = 'printf(\'<tr><th>%s</th><th>%s</th></tr>\', $row["time"], $row[\'changes\']);';
    $end = 'echo \'</table>\';';
    query($sql, $start, $data, $end);
  }
  // FIGURE OUT TODAY'S DAY
  function getDay($out) {
    switch ($out) {
      case 'string':
        return date('D');
        break;
      case 'int':
        return getDayInt(date('D'));
        break;
    }
  }
  // CONVERTER FROM DAY TO DAY INT
  function getDayInt($day) {
    switch ($day) {
      case 'Mon':case 'Monday':
        return 0;
        break;
      case 'Tue':case 'Tuesday':
        return 1;
        break;
      case 'Wed':case 'Wednesday':
        return 2;
        break;
      case 'Thu':case 'Thursday':
        return 3;
        break;
      case 'Fri':case 'Friday':
        return 4;
        break;
      case 'Sat':case 'Saturday':
        return 5;
        break;
      case 'Sun':case 'Sunday':
        return 6;
        break;
      default:
        return -1;
        break;
    }
  }
  /// SUPRISE PREPARED FOR 1 APRIL
  function fool() {
    include 'requests\april.php';
  }
  // DISPLAY INSTRUCTIONS TO THE USER
  function splash() {
    echo "<br><br><h2>Hello, there. To start, click the relevant course.</h2>";
  }
?>
