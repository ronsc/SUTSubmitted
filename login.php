<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
  <title>ระบบตรวจ Code [C/C++] - Comprotournament#5</title>
  
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" href="kube/css/kube.min.css" />
  <link rel="stylesheet" href="kube/mystyle.css" />
<link rel="stylesheet" href="kube/font-awesome/css/font-awesome.min.css">

  <script src="kube/jquery.js"></script>
  <script src="kube/js/kube.min.js"></script>

<style>
	body {
		background-image: url('img/logo.jpg');
		background-position: top center;
		background-repeat: no-repeat;
		background-color: #70c4ff;

		margin-top: 250px;
	}
</style>
</head>

<body OnLoad="document.form.id.focus();">

<div class="units-row">
	<div class="unit-centered unit-40 bg-white padding-10" style="border-radius: 10px;">
<form method="post" action="authen.php" name="form" class="forms">

<fieldset style="background-color: #; font-size: 14pt;">
        <legend>
        	<font size=4>เข้าสู่ระบบ</font>
        </legend>
        <div align="center">
        <div class="input-groups">
        <span class="input-prepend"><i class="fa fa-user"></i></span>
        <input type="text" name="id" placeholder="ชื่อผู้ใช้"/>
    </div> <br />
    <div class="input-groups">
        <span class="input-prepend"><i class="fa fa-unlock"></i></span>
        
        <input type="password" name="pass" class="width-70" placeholder="รหัสผ่าน"/>
    </div> <br />
    <p>
    	<input type="submit" name="okay" value="ล็อกอิน" class="btn btn-blue" />
    	<input type="reset" name="reset" value="ยกเลิก" class="btn" />
    </p>
</div>
</fieldset>
<center><b>
<?php

if(isset($_GET['error'])) {
  $p_error = $_GET['error'];
  if($p_error==1) {
    echo '<font size=4 color=red>ไม่พบผู้ใช้ในฐานข้อมูล, กรุณาเข้าใช้งานอีกครั้ง</font>';
  }
}
?>
</b></center>
</form>
	</div>
</div>

</body>
</html>
