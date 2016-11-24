<!DOCTYPE html>
<html>
<head>
	<title>Edit vocab_words</title>
	<link rel="stylesheet" type="text/css" href="../search/css.css">
</head>
<body>
	<?php
	include '../search/connect.php';
	$id = $_GET['id'];
	$txtWord = $_GET['txtWord'];
	$txtLevel = $_GET['txtLevel'];
	$stmt=$conn->query("SELECT * FROM vocab_words WHERE id='".$id."'");
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$data = $stmt->fetch();
	if(isset($_POST['edit'])){
		if($_POST['word']==""){
			$errorWord = "Please fill in this text.";
		}else{
			$get_Word = $_POST['word'];
		}
		if($_POST['level']===""){
			$errorLevel = "Please fill in this text.";
		}else{
			$get_Level = $_POST['level'];
		}
		if($get_Word && $get_Level){
			try {
				$sql = "UPDATE vocab_words SET word = :word, level= :level WHERE id= :id";
				$stmt=$conn->prepare($sql);
				$stmt->bindParam(':word', $get_Word, PDO::PARAM_STR);
				$stmt->bindParam(':level', $get_Level, PDO::PARAM_INT);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
				echo "<h2>Successfully</h2>";
			} catch (PDOException $e) {
				echo "Error ".$e->Message();
			}
		}
	}
	if(isset($_POST['cancel'])){
		header("location:import.php?id=".$id."&txtLevel=".$txtLevel."&txtWord=".$txtWord."");
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
						<input type="submit" name="edit" value="Edit" class="edit">
						<input type="submit" name="cancel" value="Cancel">
					</div>


				</fieldset>

			</div>
		</form>
	</div>


</body>
</html>