<!DOCTYPE html>
<html>
<head>
	<title>Edit vocab_words</title>
	<link rel="stylesheet" type="text/css" href="css.css">
</head>
<body>
	<?php
	include 'connect.php';
	$id = $_GET['id'];
	$find = $_GET['txtword'];
	$getlevel = $_GET['getlevel'];
	$kind = $_GET['kind'];
	$current_page = $_GET['page'];
	$stmt=$conn->query("SELECT * FROM vocab_words WHERE id='".$id."'");
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$data = $stmt->fetch();
	$word = $level = "";
	if(isset($_POST['edit'])){
		if($_POST['word']==""){
			$errorWord = "please fill in this";
		}else{
			$word = $_POST['word'];
		}
		if($_POST['level']===""){
			$errorLevel = "please fill in this";
		}else{
			$level = $_POST['level'];
		}
			// EDIT DATABASE
		if($word && $level !== ""){
			try {
				$sql = "UPDATE vocab_words SET word = :word, level = :level WHERE id = :id";
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':word', $_POST['word'], PDO::PARAM_STR);
				$stmt->bindParam(':level', $_POST['level'], PDO::PARAM_STR);
				$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
				$stmt->execute();
				header("location:search.php?id=".$id."&txtword=".$find."&getlevel=".$getlevel."&page=".$current_page."&kind=".$kind."");
			} catch (PDOException $e) {
				echo "Error ". $e->getMessage();
			}
		}
	}
	if(isset($_POST['cancel'])){
		header("location:search.php?id=".$id."&txtword=".$find."&getlevel=".$getlevel."&page=".$current_page."&kind=".$kind."");
	}
	?>
	<div class="edit_total">
		<form method="post" action="">
			<div class="total_edit">


			<fieldset>
				<div class="edit_header_container">

					<div class="edit_header_one">
						<h3>Word</h3>
					</div>
					<div class="edit_header_two">
						<input type="text" name="word" value="<?php echo $data['word']; ?>">
					</div>

				</div>


				<span class="error"><?php isset($errorWord)? $errorWord : ""; ?></span>
				<br>


				<div class="edit_content_container">
					<div class="edit_header_one">
						<h3>Level</h3>
					</div>
					<div class="edit_header_two">
						<input type="text" name="level" value="<?php echo $data['level']; ?>">
					</div>
					
				</div>


				<span class="error"><?php isset($errorLevel)? $errorLevel : ""; ?></span>
				<br>


				<div class="button_edit">
					<input type="submit" name="edit" value="Edit">
					<input type="submit" name="cancel" value="Cancel">
				</div>

				</fieldset>



			</div>
		</form>
	</div>
</body>
</html>