<?php
// Create connection
    $connection = new mysqli("localhost","bryan","PASSWORD","minecraft_server_status");
    // Check connection
    if($connection->connect_error)
        die("Connection failed: " . $connection->connect_error);

    $sql = "TRUNCATE TABLE snapshots";
    $result = $connection->query($sql);
    $connection->close();
?>
