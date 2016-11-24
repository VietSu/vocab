<!DOCTYPE html>
<html>
<head>
	<title>Edit vocab_categories</title>
	<link rel="stylesheet" type="text/css" href="../search/css.css">
</head>
<body>
	<?php
	include "../search/connect.php";
	$id = $_GET['id'];
	$name = $_GET['name'];
	$stmt=$conn->query("SELECT * FROM vocab_categories WHERE id='".$id."'");
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$data = $stmt->fetch();
	$name_cate = "";
	$temp=0;
	if (isset($_POST['edit'])) {
		if($_POST['name_cate']==""){
			$errorName = "Please fill in here." ;
		}else{
			$name_cate = $_POST['name_cate'];
		}
		if($name_cate){
			try{
				$sql = "UPDATE vocab_categories SET name = :name_cate WHERE id = :id";
				$stmt=$conn->prepare($sql);
				$stmt->bindParam(':name_cate',$name_cate,PDO::PARAM_STR);
				$stmt->bindParam(':id',$id,PDO::PARAM_INT);
				$stmt->execute();
				$temp++;
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}
	}
	if($temp!=0 || isset($_POST['cancel'])){
		header("location:add.php");
	}
	?>
	<div class="edit_total">
		<form method="post" action="">
			<div class="total_edit">


				<fieldset>


					<div class="edit_header_container">

						<div class="edit_header_one">
							<h3>Name</h3>
						</div>
						<div class="edit_header_two">
							<input type="text" name="name_cate" value="<?php echo $data['name']; ?>">
						</div>

					</div>	


					<span class="error"><?php isset($errorName)? $errorName : ""; ?></span>
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