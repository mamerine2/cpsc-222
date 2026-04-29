<?php
session_start();

function showHeader() {
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>CPSC222 Final Exam</title>";
    echo "</head>";
    echo "<body>";
    echo "<h1>CPSC222 Final Exam</h1>";
}

function showFooter() {
    echo "<hr>";
    echo date("Y-m-d h:i:s A");
    echo "</body>";
    echo "</html>";
}

function showLoginForm($error = "") {
    if ($error != "") {
        echo "<p style='color:red;'>$error</p>";
    }

    echo "<form method='post' action='final.php'>";
    echo "Username: <input type='text' name='username'><br>";
    echo "Password: <input type='password' name='password'><br>";
    echo "<input type='submit' name='login' value='Login'>";
    echo "</form>";
}

showHeader();

if (!isset($_SESSION['username'])) {
    showLoginForm();
}

showFooter();
?>
