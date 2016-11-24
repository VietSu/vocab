<?php
	$id = $_GET['id'];
	$name = $_GET['name'];
	include '../search/connect.php';
	$sql="DELETE FROM vocab_categories WHERE id = :id AND name = :name ";
	try {
		$stmt=$conn->prepare($sql);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->bindParam(':name',$name,PDO::PARAM_STR);
		$stmt->execute();
		header("Refresh:0; url=add.php");
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
?>