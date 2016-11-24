<!DOCTYPE html>
<html>
<head>
	<title>Import data</title>
	<link rel="stylesheet" type="text/css" href="../search/css.css">
</head>
<?php include '../search/connect.php'; ?>
<body>
	<div class="menu">
		<ul>
			<li><a href="../search/search.php">Search</a></li>
			<li><a href="../category/add.php">Category</a></li>
			<li class="active"><a href="import.php">Import</a></li>
		</ul>
	</div>
	<div class="total">
		
		<div class="header">
			<fieldset>
				<div class="header_container">

					<div class="header_one">
						<form method="get" action="">
							<?php
							if (isset($_GET['txtWord'])) {
								$word = $_GET['txtWord'];
							}else{
								$word = "";
							}
							if (isset($_GET['level'])) {
								$level = $_GET['level'];
							}else{
								$level = "";
							}
							if (isset($_GET['name_cate'])) {
								$name_cate = $_GET['name_cate'];
							}else{
								$name_cate = "";
							}
							?>
							<div class="header_one_one">
								<div id="add_word"><h3>Add Word: </h3></div>
							</div>
							<div class="header_one_two">
								<textarea name="txtWord" rows="5" cols="20"></textarea>
							</div>
						</div>



						<div class="header_two">

							<div class="header_two_one">
								<h3>Level: </h3>
							</div>


							<div class="header_two_two">
								<select name="level">
									<?php
									$stmt=$conn->query("SELECT distinct level FROM vocab_words order by level asc");
									$stmt->setFetchMode(PDO::FETCH_ASSOC);
									while($row=$stmt->fetch()){
										?>
										<option <?php if(isset($level) && $level==$row['level']){echo "selected='selected'";} ?>  value="<?php echo $row['level']; ?>">
											<?php echo $row['level']; ?>
										</option>
										<?php } ?>
									</select>
								</div>


							</div>



							<div class="header_three">
								<div class="header_three_one">
									<h3>Category: </h3>
								</div>
								<div class="header_three_two">


									<select name="name_cate">
										<option <?php if(isset($name_cate) && $name_cate=='none'){echo "selected='selected'";} ?> value="none">None</option>
										<?php
										$sql = "SELECT * FROM vocab_categories";
										$stmt=$conn->query($sql);
										$stmt->setFetchMode(PDO::FETCH_ASSOC);
										while($row=$stmt->fetch()){
											?>
											<option <?php if(isset($name_cate) && $name_cate==$row['name']){echo "selected='selected'";} ?> value="<?php echo $row['id']; ?>">
												<?php echo $row['name']; ?>
											</option>
											<?php } ?>
										</select>
									</div>
								</div>




								<div class="header_four">
									<input type="submit" name="add" value="Add">
								</div>

							</div>
						</form>
					</fieldset>
				</div>
			</div>



			<?php
			if(isset($_GET['add'])){
				$word = $_GET['txtWord'];
				$level = $_GET['level'];
				$name_cate = $_GET['name_cate'];
				if($word){
					$data = explode(' ', $word);
					$sql = "SELECT vocab_words.word FROM vocab_words";
					$stmt = $conn->query($sql);
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$check_word = array();
					while($row=$stmt->fetch()){
						$check_word[] = $row['word'];
					}
					$export = 0;
					for($i=0; $i<count($data);$i++){
						if(!(in_array($data[$i], $check_word)) && $data[$i]!=""){
							try {
								$sql_word = "INSERT INTO vocab_words(word, level) VALUES(:word, :level)";
								$conn->beginTransaction();
								$stmt = $conn->prepare($sql_word);
								$stmt-> bindParam(':word', $data[$i], PDO::PARAM_STR);
								$stmt-> bindParam(':level', $level, PDO::PARAM_INT);
								$stmt->execute();
								$conn->commit();
								$export++;
							} catch (PDOException $e) {
								echo $e->getMessage();
							}
							if($name_cate!='none'){
								$sql_cate = "SELECT id FROM vocab_words WHERE word= '".$data[$i]."' LIMIT 1";
								$stmt = $conn->query($sql_cate);
								$stmt->setFetchMode(PDO::FETCH_ASSOC);
								$row=$stmt->fetch();
								$id_word = $row['id'];

								$id_category = $name_cate;

								try {
									$sql_word_category = "INSERT INTO vocab_word_categories(word_id, category_id) VALUES(:word_id, :category_id)";
									$conn->beginTransaction();
									$stmt = $conn->prepare($sql_word_category);
									$stmt-> bindParam(':word_id', $id_word, PDO::PARAM_INT);
									$stmt-> bindParam(':category_id', $id_category, PDO::PARAM_INT);
									$stmt->execute();
									$conn->commit();
									$export++;
								} catch (PDOException $e) {
									echo $e->getMessage();
								}
							}
						}
					}
					if($export!=0){
						echo "<h3 align='center'>Successfully</h3>";
					}else{
						echo "<h3 align='center'>Fail</h3>";
					}

					?>


					<div class="list">
						<fieldset>
							<legend><h3>Listword:</h3></legend>
							<div class='table'>


								<table>
									<tr>
										<th>ID</th>
										<th>Word</th>
										<th>Level</th>
										<th>Edit</th>
										<th>Delete</th>
									</tr>
									<?php
									for($i=0;$i<count($data);$i++){
										$sql = "SELECT * FROM vocab_words WHERE word = '".$data[$i]."' AND level= '".$level."'";
										$stmt=$conn->query($sql);
										$stmt->setFetchMode(PDO::FETCH_ASSOC);
										while($row=$stmt->fetch()){
											?>
											<tr>
												<td><?php echo $row['id']; ?></td>
												<td><?php echo $row['word']; ?></td>
												<td><?php echo $row['level']; ?></td>
												<td><?php echo "<a href='edit.php?id=".$row['id']."&txtLevel=".$row['level']."&txtWord=".$row['word']."'>Edit</a>"; ?></td>
												<td><?php echo "<a href='delete.php?id=".$row['id']."&txtLevel=".$row['level']."&txtWord=".$row['word']."'>Delete</a>"; ?></td>
											</tr>
											<?php 
										}
									}
									?>
								</table>

							</div>

							<?php
						}
					}
					?>

				</fieldset>
			</div>

		</div>
	</div>
</body>
</html>