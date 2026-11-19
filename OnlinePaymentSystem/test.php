<?php

include "db.php";

echo "Connected successfully<br><br>";

$result = $conn->query("SHOW TABLES");

while ($row = $result->fetch_array()) {
    echo $row[0] . "<br>";
}

?>