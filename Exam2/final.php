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

function checkLogin($username, $password) {
    $lines = file("auth.db");

    foreach ($lines as $line) {
        $parts = preg_split("/\s+/", trim($line));

        if (count($parts) == 2) {
            $storedUsername = $parts[0];
            $storedPassword = $parts[1];

            if ($username == $storedUsername && password_verify($password, $storedPassword)) {
                return true;
            }
        }
    }

    return false;
}

showHeader();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (checkLogin($username, $password)) {
        $_SESSION['username'] = $username;
        echo "<h2>Welcome, " . $_SESSION['username'] . "! ";
        echo "(<a href='final_logout.php'>Log Out</a>)</h2>";
        echo "<p>Dashboard:</p>";
        echo "<ul>";
        echo "<li><a href='final.php?page=1'>User list</a></li>";
        echo "<li><a href='final.php?page=2'>Group list</a></li>";
        echo "<li><a href='final.php?page=3'>Syslog</a></li>";
        echo "</ul>";
    } else {
        showLoginForm("Invalid login");
    }
} else {
    if (isset($_SESSION['username'])) {
        echo "<h2>Welcome, " . $_SESSION['username'] . "! ";
        echo "(<a href='final_logout.php'>Log Out</a>)</h2>";
        echo "<p>Dashboard:</p>";
        echo "<ul>";
        echo "<li><a href='final.php?page=1'>User list</a></li>";
        echo "<li><a href='final.php?page=2'>Group list</a></li>";
        echo "<li><a href='final.php?page=3'>Syslog</a></li>";
        echo "</ul>";
    } else {
        showLoginForm();
    }
}

showFooter();
?>
