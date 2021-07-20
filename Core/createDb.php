<?php
$dbName = 'myphonebook';
try {
    $conn = new PDO("mysql:host=localhost", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Now since I have been connected, I want to check DB existence.
    $databases = $conn->query('show databases')->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array($dbName, $databases)) {
        $conn->exec("CREATE DATABASE `$dbName`")
        or die(print_r($conn->errorInfo(), true));
    }

        //creating tables

    $db = new PDO("mysql:dbname=$dbName;host=localhost", "root", "");
    $sql ="CREATE TABLE IF NOT EXISTS users(
                         id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                         username VARCHAR( 150 ) NOT NULL, 
                         fullname VARCHAR( 250 ) NOT NULL,
                         email VARCHAR( 255 ) NOT NULL, 
                         password VARCHAR( 150 ) NOT NULL, 
                         salt VARCHAR( 150 ) NOT NULL";

        $session_table="CREATE TABLE IF NOT EXISTS users_session(
                        id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                        user_id INT(11) ,
                        hash varchar(150))";

    $contact_table="CREATE TABLE IF NOT EXISTS contacts(
                        id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                        user_id INT(11) ,
                        ContactName VARCHAR (150),
                        ContactAddress VARCHAR (150),
                        Number_1 VARCHAR (255), 
                        Number_2 VARCHAR (255),
                        Number_3 VARCHAR (255))";
    $db->exec($sql);
    $db->exec($session_table);
    $db->exec($contact_table);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}