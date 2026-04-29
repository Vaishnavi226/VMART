<?php
$conn = new PDO("mysql:host=127.0.0.1", "root", "");
$conn->exec("DROP DATABASE IF EXISTS vmart");
$conn->exec("CREATE DATABASE vmart");
$conn->exec("USE vmart");

$sql = file_get_contents("database_new.sql");
$conn->exec($sql);
echo "Database imported successfully!";
?>