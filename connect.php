<?php

    $servername = "jfrpocyduwfg38kq.chr7pe7iynqr.eu-west-1.rds.amazonaws.com";
    $username = "uzbwbfu8fpa63ede";
    $password = "bd8fhd378x2fuvdj";
    $database = "n4xzjd0f0ks0aq5y";
    $port = 3306;

    $conn = new mysqli($servername, $username, $password, $database, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Connected successfully";

?>
