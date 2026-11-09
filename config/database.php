
<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "coastal_villa"
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>

