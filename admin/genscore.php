<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Gen Score</title>

</head>

<body>
<form class="forms forms-inline" action="admin/genreport.php" method="get" target="_blank">

<div class="tools-alert tools-alert-blue" align="center" style="padding: 50px;">
<font size=5>เวลาเริ่มการแข่งขัน : 
<select name="hour" style="font-size: 16pt; width: 100px;">
	<?php
		for($i=0; $i<=23; $i++){
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	?>
</select> 
:
<select name="minute" style="font-size: 16pt; width: 100px;">
	<?php
		for($i=0; $i<=59; $i++){
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	?>
</select>
น. </font>
<button type="submit" class="btn btn-blue">ดูคะแนน</button>
</div>

</form>
</body>
</html>
