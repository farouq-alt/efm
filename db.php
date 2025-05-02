<?php
$conn = mysqli_connect("localhost", "root", "", "assurance");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
