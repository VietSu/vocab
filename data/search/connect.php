<?php
	$servername = "localhost";
	$username = "root";
	$pasword = "1234";
	try{
		$conn = new PDO("mysql:host=$servername; dbname=vocab_db", $username, $pasword);
		$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e){
		echo "Connection failed : " . $e->getMessage();
	}
?>