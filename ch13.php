<?php
session_start();

function clean_input($value)
{
    $value = trim($value);
    $value = preg_replace('/[^a-zA-Z0-9]/', '', $value);
    return $value;
}

function destroy_session_and_data()
{
    $_SESSION = array();
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
}

$message = "";

if (isset($_GET['logout']))
{
    destroy_session_and_data();
}

if (isset($_POST['username']) && isset($_POST['password']))
{
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);

    if ($username == "admin" && $password == "password")
    {
        $_SESSION['username'] = $username;
    }
    else
    {
        $message = "Invalid login...";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Ch 13</title>
</head>
<body>

<?php
if (isset($_SESSION['username']))
{
    echo "<h1>Hello, " . $_SESSION['username'] . "</h1>";
    echo "<a href='ch13.php?logout=1'>Logout</a>";
}
else
{
    if ($message != "")
        echo "<p>$message</p>";

    echo <<<_END
    <form method="post" action="ch13.php">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
_END;
}
?>

</body>
</html>
