<!DOCTYPE html>
<html>
<head>
	<title>Show categoty</title>
	<link rel="stylesheet" type="text/css" href="../search/css.css">
</head>
<body>
	<div class="menu">
		<ul>
			<li><a href="../search/search.php">Search</a></li>
			<li class="active"><a href="add.php">Category</a></li>
			<li><a href="../import/import.php">Import</a></li>
		</ul>
	</div>
	<div class="total">
		<div class="header">
			<fieldset>
				<legend><h3>List Category:</h3></legend>
				<div class="header_container">
					<table>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
						<?php
						include '../search/connect.php';
						$sql = "SELECT * FROM vocab_categories ";
						$stmt=$conn->query($sql);
						$stmt->setFetchMode(PDO::FETCH_ASSOC);
						while($row=$stmt->fetch()){
							?>
							<tr>
								<td><?php echo $row['id']; ?></td>
								<td><?php echo $row['name']; ?></td>
								<td><?php echo "<a href='edit.php?id=".$row['id']."&name=".$row['name']."'>Edit</a>"; ?></td>
								<td><?php echo "<a href='delete.php?id=".$row['id']."&name=".$row['name']."'>Delete</a>"; ?></td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>
			</fieldset>
		</div>
		<div class="footer">
			<?php
			if(isset($_POST['add'])){
				if($_POST['txtName']===""){
					$errorName = "Please fill in name at here";
				}else{
					$name = $_POST['txtName'];
					$sql = "SELECT vocab_categories.name FROM vocab_categories ";
					$stmt=$conn->query($sql);
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$array = array();
					while($row=$stmt->fetch()){
						$array[] = $row['name'];
					}
					if(in_array($name, $array)){
					}else{
						try{
							$sql = "INSERT INTO vocab_categories(name) VALUES(:name)";
							$stmt=$conn->prepare($sql);
							$stmt->bindParam(':name',$name);
							$stmt->execute();
							echo "<h3 align='center'>Successfully</h3>";
						}catch(PDOException $e){
							echo $e->getMessage();
						}
					}
				}
			}
			?>
		</div>
		<div class="content">
			<fieldset>
				<legend><h3>Add Category:</h3></legend>	
				<div class="table">
					<form action="" method="post">
						<span style="color: #346699; font-size: 20px">Name:</span>
						<label>&nbsp;</label>
						<input type="text" name="txtName">
						<span class="error"><?php if(isset($errorName) && isset($_POST['add'])){
							echo $errorName;
						}else{
							echo "";
						} ?></span>
						<label>&nbsp;</label>
						<input type="submit" name="add" value="Add">
					</form>
				</div>
			</fieldset>
		</div>
	</div>
</body>
</html>