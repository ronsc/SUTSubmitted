<?php

include_once '../config.php';
include_once '../util.php';
include_once '../db.php';



function ljust($st, $w)
{
  $ost = $st;
  for($i=0; $i<$w-strlen($st); $i++)
    $ost = $ost . "&nbsp;";
  return $ost;
}

function rjust($st, $w)
{
  $ost = "";
  for($i=0; $i<$w-strlen($st); $i++)
    $ost = $ost . "&nbsp;";
  $ost = $ost . $st;
  return $ost;
}

function getuserlist()
{
  global $user_list;

  $res = mysql_query("SELECT user_id FROM user_info WHERE (type='C') or (type='A') ORDER BY grp, user_id");
  $row=mysql_num_rows($res);
  for($i=0; $i<$row; $i++) {
    $user_list[$i] = mysql_result($res,$i,'user_id');
  }
}

function getproblist()
{
  global $prob_list;

  $res = mysql_query("SELECT prob_id FROM prob_info WHERE (avail='Y')");
  $row=mysql_num_rows($res);
  for($i=0; $i<$row; $i++) {
    $prob_list[$i] = mysql_result($res,$i,'prob_id');
  }
}

function gentime($uid, $pid, &$lasttime)
{
	$res = mysql_query("SELECT sub_num, time FROM submission " .
                     "WHERE (user_id='$uid') and (prob_id='$pid') ".
					 "ORDER BY sub_num DESC");
	$row=mysql_num_rows($res);
  if($row!=0) {
	  $time = mysql_result($res,0,'sub_num');
	  $lasttime = mysql_result($res,0,'time');
    return $time*20;
  } else {
    return 0;
  }
}

function gengrd_result($uid, $pid, &$score, &$sumtime)
{
  $res = mysql_query("SELECT score, grading_msg FROM grd_status " .
                     "WHERE (user_id='$uid') and (prob_id='$pid')");
  $row=mysql_num_rows($res);
  $hour = $_GET["hour"]-6;
	$minute = $_GET["minute"];
  if($row!=0) {
    $score = mysql_result($res,0,'score');
	$time = gentime($uid, $pid, $lasttime);
	$subtime = 0;
	if($score == 100){
		$time = $time - 20;
		$lasttime = substr($lasttime,11);
		list($hourc, $minutec, $second) = split(":",$lasttime,3);
		$subtime = intval($hourc) - intval($hour);
		$subtime = $subtime + (intval($minutec) - intval($minute));
		//echo $subtime." ".$time."<br>";
	}
	$sumtime = ($subtime+$time);
    return 
      rjust(mysql_result($res,0,'score'),4) . "%&nbsp;&nbsp;" . 
      mysql_result($res,0,'grading_msg') . "&nbsp;&nbsp;TimeInUse+FailTime = " .
	  $subtime . "+" . $time . " = " . ($subtime+$time);
  } else {
    $score = 0;
    return "&nbsp;&nbsp;&nbsp;0&nbsp;&nbsp;N";
  }
}

function genreport_user($uid)
{
  global $prob_list;

  $totalscore = 0;
  $totaltime = 0;

  echo "-------------------------------------------------------<br>\n";
  $res = mysql_query("SELECT user_id, name, grp FROM user_info " .
                     "WHERE (user_id='$uid')");
  $row=mysql_num_rows($res);
  echo "user: " . $uid . " : " . mysql_result($res,0,'name') . "<br>\n";
  echo "center: " . mysql_result($res,0,'grp') . "<br>\n";
//  echo "task&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;score&nbsp;&nbsp;grading result<br>\n";
//  echo "-------------------------------------------------------<br>\n";
  for($i=0; $i<count($prob_list); $i++) {
	  $sumtime = 0;
    $msg = gengrd_result($uid, $prob_list[$i], $score, $sumtime);
	if($score==100){
    	$totalscore += 1;
	}
	$totaltime += $sumtime;
    echo 
      ljust($prob_list[$i],10) . "  " . $msg . "<br>\n";
  }
//  echo "<br>\n";
  echo "TOTAL : &nbsp;&nbsp;&nbsp;" . rjust($totalscore,4) . "<br>\n";
  echo "TOTAL_TIME : &nbsp;&nbsp;&nbsp;" . rjust($totaltime,6) . "<br>\n";
//  echo "***<br>\n";
//  echo "============<br>\n";
}

function genreport()
{
  global $user_list, $prob_list;
	
  for($i=0; $i<count($user_list); $i++)
    genreport_user($user_list[$i]);
}

checkauthen();
if($_SESSION['type']!=USERTYPE_ADMIN) {
  echo 'You do not have the permission to access this script.';
  exit;
}
connect_db();
getuserlist();
getproblist();
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>

<body>
<tt>
<?php genreport(); ?>
</tt>

</body>

</html>

<?php
close_db();
?>
