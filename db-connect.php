<?php
$host     = 'localhost';
$username = 'root';
$password = '';
$dbname   ='dummy_db.sql1';

$conn = new mysqli($host, $username, $password, $dbname);
if(!$conn){
    die("Cannot connect to the database.". $conn->error);
}