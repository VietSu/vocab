<?php
	$id=$_GET['id'];
	$txtLevel = $_GET['txtLevel'];
	$txtWord = $_GET['txtWord'];
	include '../search/connect.php';
	try {
		$sql = "DELETE FROM vocab_words WHERE id= :id AND level= :level AND word= :word";
		$stmt=$conn->prepare($sql);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->bindParam(':level',$txtLevel,PDO::PARAM_INT);
		$stmt->bindParam(':word',$txtWord,PDO::PARAM_STR);
		$stmt->execute();
		echo "<h2>Successfully</h2>";
	} catch (PDOException $e) {
		echo "Error ". $e->getMessage();
	}
?>