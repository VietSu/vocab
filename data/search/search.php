  <!DOCTYPE html>
  <html>
  <head>
  	<title>Search</title>
  	<link rel="stylesheet" type="text/css" href="css.css">
  </head>
  <body>
  	<?php include 'connect.php'; ?>
  	<div class="menu">
  		<ul>
  			<li class="active"><a href="search.php">Search</a></li>
  			<li><a href="../category/add.php">Category</a></li>
  			<li><a href="../import/import.php">Import</a></li>
  		</ul>
  	</div>
  	<div class="total">

  		<div class="header">
  			<fieldset>
  				<legend><h3>Input:</h3></legend>
  				<div class="header_container">
  					<form action="" method="get">
  						<div class="header_one"> 
  							<?php
  							if (isset($_GET['txtword'])){
  								$find =$_GET['txtword'];
  							}else{
  								$find = '';
  							}

  							if (isset($_GET['getlevel'])) {
  								$getlevel= $_GET['getlevel'];
  							}else{
  								$getlevel= '';
  							}

  							if(isset($_GET['kind'])){
  								$kind = $_GET['kind'];
  							}else{
  								$kind = '';
  							}
  							?>
  							<div class="header_one_one">
  								<h3>Word find: </h3>
  							</div>
  							<div class="header_one_two">
  								<input type="text" name="txtword" value="<?php echo $find; ?>">
  							</div>
  						</div>
  						<div class="header_two">
  							<div class="header_one_one"><h3>Level: </h3></div>
  							<div class="header_two_two">
  								<select name="getlevel" class="select_level">
  									<option <?php if(isset($getlevel) && $getlevel=='ALL'){ echo "selected='selected'";} ?> value="ALL">All</option>
  									<?php
  									$stmt=$conn->query("SELECT distinct level FROM vocab_words order by level asc");
  									$stmt->setFetchMode(PDO::FETCH_ASSOC);
  									while($row=$stmt->fetch()){
  										?>
  										<option <?php if(isset($getlevel) && $getlevel==$row['level']){ echo "selected='selected'";} ?> value="<?php echo $row['level']; ?>">
  											<?php
  											echo $row['level'];
  											?>	
  										</option>
  										<?php
  									} 
  									?>
  								</select>
  							</div>
  						</div>
  						<div class="header_three">
  							<div class="header_one_one"><h3>Category: </h3></div>
  							<div class="header_three_two">
  								<select name="kind">
  									<option <?php if(isset($kind) && $kind=='ALL'){echo "selected='selected'";} ?> value="ALL">All</option>
  									<?php
  									include 'connect.php';
  									$stmt=$conn->query("SELECT name FROM vocab_categories");
  									$stmt->setFetchMode(PDO::FETCH_ASSOC);
  									while($row=$stmt->fetch()){
  										?>
  										<option <?php if(isset($kind) && $kind==$row['name']){echo "selected='selected'";} ?> value="<?php echo $row['name']; ?>">
  											<?php
  											echo $row['name'];
  										}
  										?>
  									</option>
  								</select>
  							</div>
  						</div>
  						<div class="header_four"><input type="submit" name="find" value="Search"> </div>
  					</form>
  				</div>
  			</fieldset>
  		</div>

  		<div class="content">
  			<fieldset>
  				<legend><h3>Listword:</h3></legend>
  				<div class='table'>
  					<?php
  					include 'connect.php';
  					if(isset($_GET['txtword'])){
  						$find = $_GET['txtword'];
  						$getlevel = $_GET['getlevel'];
  						$kind = $_GET['kind'];
  						$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
  						$limit = 30;
  						$start = ($current_page - 1) * $limit;
  						$temp = "WHERE word LIKE '".$find."%'";
  						$inner = "";
  						if($getlevel!='ALL'){
  							$temp .= "AND level= '{$getlevel}'";
  						}
  						if($kind!='ALL'){
  							$inner .= "
  							inner join vocab_word_categories on vocab_words.id = vocab_word_categories.word_id 
  							inner join vocab_categories on vocab_categories.id = vocab_word_categories.category_id";
  							$temp .= "AND name= '{$kind}'";
  						}
  						$query = "SELECT distinct vocab_words.id, vocab_words.word ,vocab_words.level FROM vocab_words {$inner} {$temp} order by vocab_words.id asc LIMIT $start, $limit";
  						$count = "SELECT count(*) FROM vocab_words {$inner} {$temp}";
  						$stmt=$conn->query($query);
  						$stmt->setFetchMode(PDO::FETCH_ASSOC);
  						?>
  						<form method="post" action="">
  							<table>
  								<tr>
  									<th>Choose</th>
  									<th>ID</th>					
  									<th>Word</th>						
  									<th>Level</th>
  									<th>Edit</th>
  									<th>Delete</th>
  									<th>Show Categories</th>					
  								</tr>
  								<?php
  								while ($row=$stmt->fetch()) {
  									?>
  									<tr>
  										<td><input type="checkbox" name="choose[]" value="<?php echo $row['id']; ?>"></td>
  										<td><?php echo $row['id']; ?></td>
  										<td><?php echo $row['word']; ?></td>
  										<td><?php echo $row['level']; ?></td>
  										<td><?php echo "<a href='edit.php?id=".$row['id']."&txtword=".$find."&getlevel=".$getlevel."&page=".$current_page."&kind=".$kind."'>Edit</a>"; ?></td>
  										<td><?php echo "<a href='delete.php?id=".$row['id']."&txtword=".$find."&getlevel=".$getlevel."&page=".$current_page."&kind=".$kind."'>Delete</a>"; ?></td>
  										<td><?php echo "<a href='showroom.php?id=".$row['id']."&txtword=".$find."&getlevel=".$getlevel."&page=".$current_page."&kind=".$kind."'>Show Categories</a>"; ?></td>
  									</tr>
  									<?php
  								}
  								$stmt=$conn->query($count);
  								$stmt->setFetchMode(PDO::FETCH_ASSOC);
  								$count_row = 0;
  								while($row = $stmt->fetch()){
  									$count_row = $row['count(*)'];
  								}
  								$total_page = ceil($count_row / $limit);
  								if ($current_page > $total_page){
  									$current_page = $total_page;
  								}else if ($current_page < 1){
  									$current_page = 1;
  								}
  								?>
  							</table>
  						</div>

  						<div class="pagination">
                
  							<div class="total_record">
  								<div class="total_record_one"><h3>Total record : </h3></div>
  								<div class="total_record_two"><h3><?php echo $count_row; ?></h3></div>
  							</div>

  							<div class="page_page">
  								<?php 
  								if ($current_page > 1 && $total_page > 1){
  									echo '<a class= "prev" href="search.php?txtword='.$find.'&getlevel='.$getlevel.'&kind='.$kind.'&page='.($current_page-1).'">«Prev</a> ';
  								}
  								$start=$end=$tmp=0;
  								if($current_page-5>=1){
  									$start=$current_page-5;
  								}else{
  									$start=1;
  									$end=10;
  								}
  								if($current_page+5<=$total_page){
  									$end=$current_page+5;
  									if($end<=10){
  										$end = 11;
  									}
  								}else{
  									$end=$total_page;
  									$tmp=$total_page-9;
  								}
  								if($tmp==0)
  								{
  									for($i=$start;$i<=$end;$i++)
  									{
                      if($i==$current_page){
                        echo '<span class="span">'.$i.'</span>';
                      }else{
                        echo '<a class="current" href="search.php?txtword='.$find.'&getlevel='.$getlevel.'&kind='.$kind.'&page='.$i.'">'.$i.'</a> ';
                      }
                    }
                  }else{
                  for($i=$tmp;$i<=$end;$i++)
                  {
                   if($i<=0){
                    echo '';
                  }else{
                   if($i==$current_page){
                    echo '<span class="span">'.$i.'</span>';
                  }else{
                    echo '<a class="current" href="search.php?txtword='.$find.'&getlevel='.$getlevel.'&kind='.$kind.'&page='.$i.'">'.$i.'</a> ';
                  }
                }		
              }
            }
          if ($current_page < $total_page && $total_page > 1){
           echo '<a class="next" href="search.php?txtword='.$find.'&getlevel='.$getlevel.'&kind='.$kind.'&page='.($current_page + 1).'">Next »</a> ';
         }
       }
       ?>
     </div>
   </div>
 </fieldset>
</div>
<div class="footer">
  <fieldset>
   <legend><h3>Category: </h3></legend>

   <div class="footer_select_category">

    <div class="footer_category"><h3>Category: </h3></div>
    <div class="select_category">
     <select name="cate">
      <?php
      include 'connect.php';
      $stmt=$conn->query("SELECT name FROM vocab_categories");
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      while($row=$stmt->fetch()){
       ?>
       <option <?php if(isset($_POST['cate']) && $_POST['cate']==$row['name']){echo "selected='selected'";} ?> value="<?php echo $row['name']; ?>">
        <?php
        echo $row['name'];
      }
      ?>
    </option>
  </select>
</div>
</div>

<div class="button">
  <input type="submit" name="add" value="Add to Category">
  <input type="submit" name="delete" value="Delete from Category">
</div>

<?php
include 'connect.php';
if(isset($_POST['add'])){
  if(isset($_POST['choose'])){
   $array = $_POST['choose'];
   if(isset($_POST['cate'])){
    $sql = "SELECT vocab_categories.id FROM vocab_categories WHERE name='".$_POST['cate']."'";
    $id_name=$conn->query($sql);
    $id_name->setFetchMode(PDO::FETCH_ASSOC);
    $category_id = "";
    while($row=$id_name->fetch()){
     $category_id = $row['id'];
   }
   $sql = "SELECT vocab_word_categories.word_id, vocab_word_categories.category_id FROM vocab_word_categories
   WHERE category_id='".$category_id."'";
   $stmt = $conn->query($sql);
   $stmt->setFetchMode(PDO::FETCH_ASSOC);
   $vocab_id = array();
   $cab_id = array();
   while($row=$stmt->fetch())
   {
     $vocab_id[] = $row['word_id'];
     $cab_id[] = $row['category_id'];
   }
   for ($i=0; $i < count($array) ; $i++) {
     if((in_array($array[$i], $vocab_id) && in_array($category_id, $cab_id))){
      echo "They are in table. You cannot insert into this.";
    }else{
      try {
       $sql = "INSERT INTO vocab_word_categories(word_id, category_id) VALUES (:word, :category)";
       $stmt = $conn->prepare($sql);
       $stmt->bindParam(':word', $array[$i], PDO::PARAM_STR);
       $stmt->bindParam(':category', $category_id, PDO::PARAM_STR);
       $stmt->execute();
       echo "<h3 align='center'>Successfully</h3>";
     } catch (PDOException $e) {
       echo "Error ". $e->getMessage();
     }

   }
 }
}
}	
}
if(isset($_POST['delete'])){
  if(isset($_POST['choose'])){
   $array = $_POST['choose'];
   if(isset($_POST['cate'])){
    $sql = "SELECT vocab_categories.id FROM vocab_categories WHERE name='".$_POST['cate']."'";
    $id_name=$conn->query($sql);
    $id_name->setFetchMode(PDO::FETCH_ASSOC);
    $category_id = "";
    while($row=$id_name->fetch()){
     $category_id = $row['id'];
   }
   $sql = "SELECT * FROM vocab_word_categories
   WHERE category_id='".$category_id."'";
   $stmt = $conn->query($sql);
   $stmt->setFetchMode(PDO::FETCH_ASSOC);
   $vocab_word_id = array();
   $vocab_id = array();
   $cab_id = array();
   while($row=$stmt->fetch())
   {
     $vocab_word_id[] = $row['id'];
     $vocab_id[] = $row['word_id'];
     $cab_id[] = $row['category_id'];
   }
   for ($i=0; $i < count($array) ; $i++) {
     if((in_array($array[$i], $vocab_id) && in_array($category_id, $cab_id))){
      try {
       $sql = "DELETE FROM vocab_word_categories WHERE word_id = :word_id AND category_id= :category_id";
       $stmt=$conn->prepare($sql);
       $stmt->bindParam(':word_id',$array[$i],PDO::PARAM_INT);
       $stmt->bindParam(':category_id',$category_id,PDO::PARAM_INT);
       $stmt->execute();
       echo "Successfull";
     } catch (PDOException $e) {
       echo "Error ". $e->getMessage();
     }

   }else{

   }
 }
}
}
}
?>
</form>
</fieldset>
</div>			
</div>
</body>
</html>