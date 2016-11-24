<?php
$start=$end=$tmp2=0;
$a = 1;
$max = 100;
if($a-5>=1){
	$start=$a-5;
}else{
	$start=1;
	$end=10;
}
if($a+5<=$max){
	$end=$a+5;
	if($end<=10){
		$end = 11;
	}
}else{
	$end=$max;
	$tmp2=$max-9;
}
if($tmp2==0)
{
	for($i=$start;$i<=$end;$i++)
	{
		if($i==$a){
			echo '<span class="span">'.$i.'</span>';
		}else{
			echo "<a>".$i."</a>";
		}
	}
}else{
	for($i=$tmp2;$i<=$end;$i++)
	{
		if($i<=0){
			echo '';
		}else{
			if($i==$a){
				echo '<span class="span">'.$i.'</span>';
			}else{
				echo "<a>".$i."</a>";
			}
		}		
	}
}
?>