<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Gen Score</title>

</head>

<body>
<form action="genreport.php" method="get">
เวลาเริ่มการแข่งขัน<br />
<select name="hour">
	<?php
		for($i=0; $i<=23; $i++){
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	?>
</select> 
:
<select name="minute">
	<?php
		for($i=0; $i<=59; $i++){
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	?>
</select> 
<br />
<input type="submit" />
</form>
</body>
</html>
