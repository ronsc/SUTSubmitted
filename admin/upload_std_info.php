<?php

include_once '../config.php';
include_once '../util.php';
include_once '../db.php';

function findid($user_id)
{
  $lid = strtolower($user_id);
  echo "[$lid]";
  $q = "select user_id from user_info where LOWER(user_id)='$lid'";
  $query = mysql_query($q);
  $r = mysql_num_rows($query);
  echo $q . " " . $r . "<br>";
  if($r==1) {
    return mysql_result($query,0,'user_id');
  } else {
    echo "error123<br>\n";
    return '';
  }
}

function update($user_id, $user_name, $passwd, $type, $group)
{
  echo $user_id . " - " . $user_name . " (" . $type . " / " . $group . ") ";

  // find user
  $query = mysql_query("select * from user_info where user_id=\"$user_id\"");
  
  if(mysql_num_rows($query)!=0) {
    // already exists.
    $q = "update user_info set name=\"" . mysql_real_escape_string($user_name). "\", " .
		 "passwd=\"" . mysql_real_escape_string($passwd) . "\", " .
         "type='" . mysql_real_escape_string($type) . "', grp='" . mysql_real_escape_string($group) . "' " .
         "where user_id=\"$user_id\""; 
    //    echo " cmd: " . $q;
    echo "[updated] <br/>";
    mysql_query($q);
  } else {
    $q = "insert into user_info (user_id, name, passwd, type, grp) values " .
         "(\"$user_id\",\"" . mysql_real_escape_string($user_name) . "\",\"" . 
		mysql_real_escape_string($passwd) . "\",\"" . mysql_real_escape_string($type) . "\",\"" . 
		mysql_real_escape_string($group) . "\")";
    //     echo " cmd: " . $q;
    mysql_query($q);
    echo "[added] <br/>";
  }

/*
  $uid=findid($user_id);
  if($uid!='')
    $q = "update user_info set name=\"$user_name\" where user_id='$uid'";
  else
    $q = "insert into user_info (user_id,name) values ('$user_id','$user_name')";
  echo "[updated]: $q <br>";
    mysql_query($q);
*/
}

function uploadfromfile($fname)
{
  $upload_count = 0;
  $linelist = file($fname);
  for($i = 0; $i<count($linelist); $i++) {
    $uinfo = explode(':',trim($linelist[$i]));
    for($j=0; $j<strlen($uinfo[0]); $j++)
      if((($uinfo[0]{$j}>='a') && ($uinfo[0]{$j}<='z')) ||
         (($uinfo[0]{$j}>='A') && ($uinfo[0]{$j}<='Z')))
        break;
    if($j<strlen($uinfo[0])) {
      $name = substr($uinfo[0],$j,strlen($uinfo[0])-$j);  
      //      echo $name . "," . $j . "<br>";
      if(count($uinfo)==5) {
        //update($name, $uinfo[1], "","","");
        update($name, $uinfo[1], $uinfo[2], $uinfo[3], $uinfo[4]);
		$upload_count++;
      }
    }
  }
  echo "<b>Uploaded " . $upload_count . " users</b><br/>";
}

checkauthen();
if($_SESSION['type']!=USERTYPE_ADMIN) {
  echo 'You do not have the permission to access this script.';
  exit;
}
?>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
</head>
<body>
<body>
<?php
if(isset($_POST['upload'])) {
  echo "<b>uploaded new user info :</b> <hr/>";
  if(($_FILES['stdfile']['size']>0) && ($_FILES['stdfile']['size']<=100000)) {
    connect_db();
    uploadfromfile($_FILES['stdfile']['tmp_name']);
    close_db();
  }
  echo "<hr/>";
} 
?>
  <form method="post" enctype="multipart/form-data">
  User info: <input type="file" name="stdfile" size="20" /><input type="submit" name="upload" value="upload" />
  </form>

  <b>Format:</b> one line per user: <br/>
  &nbsp;&nbsp;&nbsp;&nbsp;<tt>username:name:password:type:group</tt><br/>
  <tt>type</tt> is one of the following character: <tt>(C)</tt>ontestant,<tt>(S)</tt>uperviser,<tt>(A)</tt>dministrator<br/>
  
  Back to <a href="../main.php">main page</a>

</body>
</html>
