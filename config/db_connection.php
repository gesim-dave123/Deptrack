<?php
    $sName = "localhost:3307"; 
    $uName = "root"; 
    $pass = "Allourah22"; 
    $db_name = "deptrack_db";
 try{

     $conn = new PDO("mysql:host=$sName;dbname=$db_name", $uName, $pass);
     $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
         echo "Connection failed :" .$e->getMessage();
        exit(); 
    }
?>