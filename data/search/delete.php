<?php
	$find = $_GET['txtword'];
	$id = $_GET['id'];
	$getlevel = $_GET['getlevel'];
	$kind = $_GET['kind'];
	$current_page = $_GET['page'];
	include 'connect.php';
	$sql = "DELETE FROM vocab_words WHERE id = :id";
	$stmt=$conn->prepare($sql);
	$stmt->bindParam(':id',$id,PDO::PARAM_INT);
	$stmt->execute();
	header("location:search.php?id=".$id."&txtword=".$find."&getlevel=".$getlevel."&page=".$current_page."&kind=".$kind."");
?>