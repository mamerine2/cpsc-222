<?php
require 'Student.php';
require 'functions.php';

$students = array(
    new Student("Kevin", "Slonka", 1001, array(
        "CPSC222" => 98,
        "CPSC111" => 76,
        "CPSC333" => 82
    )),
    new Student("Joe", "Schmoe", 1005, array(
        "CPSC122" => 88,
        "CPSC411" => 46,
        "CPSC323" => 72
    )),
    new Student("Stewie", "Griffin", 1009, array(
        "CPSC244" => 68,
        "CPSC116" => 96,
        "CPSC345" => 82
    ))
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Chs 5-6</title>
</head>
<body>

<h1>PHP Chs 5-6</h1>

<?php
for ($i = 0; $i < count($students); $i++)
{
    echo "<table border='1' cellpadding='8' cellspacing='0' style='margin-bottom:20px;'>";

    echo "<tr>";
    echo "<td><b>Name</b></td>";
    echo "<td>" . $students[$i]->getLastName() . ", " . $students[$i]->getFirstName() . "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td><b>Student ID</b></td>";
    echo "<td>" . $students[$i]->getStudentID() . "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td><b>Grades</b></td>";
    echo "<td><ul>";

    foreach ($students[$i]->getCourses() as $course => $grade)
    {
        echo "<li>" . $course . " - " . $grade . "% " . getLetterGrade($grade) . "</li>";
    }

    echo "</ul></td>";
    echo "</tr>";

    echo "</table>";
}
?>

</body>
</html>
