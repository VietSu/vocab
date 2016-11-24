<!DOCTYPE html>
<html>
<head>
	<title>Show category</title>
	<link rel="stylesheet" type="text/css" href="css.css">
</head>
<body>

	<?php
	$id = $_GET['id'];
	include 'connect.php';
	$stmt=$conn->query("SELECT DISTINCT
		vocab_categories.id, vocab_categories.name
		FROM
		vocab_word_categories
		INNER JOIN
		vocab_categories ON vocab_categories.id = vocab_word_categories.category_id
		INNER JOIN
		vocab_words ON vocab_words.id = vocab_word_categories.word_id
		WHERE
		vocab_words.id ='".$id."'");
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	?>
	<div class="total">
		<fieldset>
			<legend><h3>Show Category:</h3></legend>
			<div class="table">
				<table>
					<tr>
						<th>ID Category</th>
						<th>Category</th>
					</tr>
					<?php
					while($row=$stmt->fetch()){
						?>
						<tr>
							<td><?php echo $row['id']; ?></td>
							<td><?php echo $row['name']; ?></td>
						</tr>
						<?php
					}
					?>
				</table>
			</div>
			<?php
			$txtword = $_GET['txtword'];
			$getlevel = $_GET['getlevel'];
			$page = $_GET['page'];
			$kind = $_GET['kind'];
			if(isset($_POST['ok'])){
				header("location:search.php?id=".$row['id']."&txtword=".$txtword."&getlevel=".$getlevel."&page=".$page."&kind=".$kind."");
			}
			if(isset($_POST['go'])){
				header("location:../category/add.php");
			}
			?>

			<div class="button">
				<form action="" method="post">
					<input type="submit" name="ok" value="OK">
					<input type="submit" name="go" value="Go Add Page">
				</form>
			</div>
		</fieldset>
	</div>

</body>
</html>
