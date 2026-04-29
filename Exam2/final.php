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
            if ($username == $parts[0] && password_verify($password, $parts[1])) {
                return true;
            }
        }
    }

    return false;
}

function showWelcome() {
    echo "<h2>Welcome, " . htmlspecialchars($_SESSION['username']) . "! ";
    echo "(<a href='final_logout.php'>Log Out</a>)</h2>";
}

function showDashboard() {
    showWelcome();

    echo "<p>Dashboard:</p>";
    echo "<ul>";
    echo "<li><a href='final.php?page=1'>User list</a></li>";
    echo "<li><a href='final.php?page=2'>Group list</a></li>";
    echo "<li><a href='final.php?page=3'>Syslog</a></li>";
    echo "</ul>";
}

function showUserList() {
    showWelcome();
    echo "<p><a href='final.php'>&lt; Back to Dashboard</a></p>";
    echo "<h3>User list</h3>";

    $lines = file("/etc/passwd");

    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>Username</th>";
    echo "<th>Password</th>";
    echo "<th>UID</th>";
    echo "<th>GID</th>";
    echo "<th>Display Name</th>";
    echo "<th>Home Directory</th>";
    echo "<th>Default Shell</th>";
    echo "</tr>";

    foreach ($lines as $line) {
        $parts = explode(":", trim($line));

        echo "<tr>";
        foreach ($parts as $part) {
            echo "<td>" . htmlspecialchars($part) . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
}

function showGroupList() {
    showWelcome();
    echo "<p><a href='final.php'>&lt; Back to Dashboard</a></p>";
    echo "<h3>Group list</h3>";

    $lines = file("/etc/group");

    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>Group Name</th>";
    echo "<th>Password</th>";
    echo "<th>GID</th>";
    echo "<th>Members</th>";
    echo "</tr>";

    foreach ($lines as $line) {
        $parts = explode(":", trim($line));

        echo "<tr>";
        foreach ($parts as $part) {
            echo "<td>" . htmlspecialchars($part) . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
}

function showSyslog() {
    showWelcome();
    echo "<p><a href='final.php'>&lt; Back to Dashboard</a></p>";
    echo "<h3>Syslog</h3>";

    $file = "/var/log/syslog";

    if (!file_exists($file)) {
        echo "<p>Could not read /var/log/syslog</p>";
        return;
    }

    $lines = file($file);

    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>Date</th>";
    echo "<th>Hostname</th>";
    echo "<th>Application[PID]</th>";
    echo "<th>Message</th>";
    echo "</tr>";

    foreach ($lines as $line) {
        $parts = preg_split('/\s+/', trim($line), 6);

        echo "<tr>";
        if (count($parts) >= 6) {
            echo "<td>" . htmlspecialchars($parts[0] . " " . $parts[1] . " " . $parts[2]) . "</td>";
            echo "<td>" . htmlspecialchars($parts[3]) . "</td>";
            echo "<td>" . htmlspecialchars($parts[4]) . "</td>";
            echo "<td>" . htmlspecialchars($parts[5]) . "</td>";
        } else {
            echo "<td colspan='4'>" . htmlspecialchars($line) . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
}

showHeader();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (checkLogin($username, $password)) {
        $_SESSION['username'] = $username;
        showDashboard();
    } else {
        showLoginForm("Invalid login");
    }
} else {
    if (!isset($_SESSION['username'])) {
        showLoginForm();
    } else {
        if (isset($_GET['page'])) {
            if ($_GET['page'] == "1") {
                showUserList();
            } else if ($_GET['page'] == "2") {
                showGroupList();
            } else if ($_GET['page'] == "3") {
                showSyslog();
            } else {
                showWelcome();
                echo "<p><a href='final.php'>&lt; Back to Dashboard</a></p>";
                echo "<p>Invalid page</p>";
            }
        } else {
            showDashboard();
        }
    }
}

showFooter();
?>
