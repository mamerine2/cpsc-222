<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $month = htmlspecialchars($_POST["month"]);
    $day = htmlspecialchars($_POST["day"]);
    $year = htmlspecialchars($_POST["year"]);
    $hour = htmlspecialchars($_POST["hour"]);
    $minute = htmlspecialchars($_POST["minute"]);
    $ampm = htmlspecialchars($_POST["ampm"]);

    if ($ampm == "PM" && $hour != 12) {
        $hour = $hour + 12;
    }
    if ($ampm == "AM" && $hour == 12) {
        $hour = 0;
    }

    $time = mktime($hour, $minute, 0, $month, $day, $year);

    // day ending
    if ($day % 10 == 1 && $day != 11) {
        $ending = "st";
    } elseif ($day % 10 == 2 && $day != 12) {
        $ending = "nd";
    } elseif ($day % 10 == 3 && $day != 13) {
        $ending = "rd";
    } else {
        $ending = "th";
    }

    $pretty = date("l", $time) . " " .
              date("F", $time) . " " .
              $day . $ending . ", " .
              $year . " - " .
              date("g:ia", $time);

    $iso = date("Y-m-d H:i:s", $time);
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Birthday Formatter</title>
</head>
<body>

<h1>Birthday Formatter</h1>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>

<p><?php echo $pretty; ?></p>

<a href="chs712.php?iso=<?php echo urlencode($iso); ?>">Show date in ISO format</a>

<?php } elseif (isset($_GET["iso"])) { ?>

<p><?php echo htmlspecialchars($_GET["iso"]); ?></p>

<?php } else { ?>

<form method="post" action="chs712.php">

<table border="1" cellpadding="5">

<tr>
<th>Month</th><th>Day</th><th>Year</th><th>Hour</th><th>Minute</th><th>AM/PM</th>
</tr>

<tr>

<td>
<select name="month">
<?php
for ($i = 1; $i <= 12; $i++) {
    echo "<option value='$i'>" . date("F", mktime(0,0,0,$i,1)) . "</option>";
}
?>
</select>
</td>

<td>
<select name="day">
<?php
for ($i = 1; $i <= 31; $i++) {
    echo "<option value='$i'>$i</option>";
}
?>
</select>
</td>

<td>
<select name="year">
<?php
for ($i = 2020; $i <= 2035; $i++) {
    echo "<option value='$i'>$i</option>";
}
?>
</select>
</td>

<td>
<select name="hour">
<?php
for ($i = 1; $i <= 12; $i++) {
    echo "<option value='$i'>$i</option>";
}
?>
</select>
</td>

<td>
<select name="minute">
<?php
for ($i = 0; $i <= 59; $i++) {
    echo "<option value='$i'>$i</option>";
}
?>
</select>
</td>

<td>
<select name="ampm">
<option value="AM">AM</option>
<option value="PM">PM</option>
</select>
</td>

</tr>

<tr>
<td colspan="6" align="center">
<input type="submit" value="Format Date">
</td>
</tr>

</table>

</form>

<?php } ?>

</body>
</html>
