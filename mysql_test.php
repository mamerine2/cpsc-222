<?php
$c = new mysqli("localhost","webuser","password","webtest");
if ($c->connect_error) { die("fail"); }
echo "ok";
?>
