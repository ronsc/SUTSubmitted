<?php

include_once 'config.php';
include_once 'db.php';
include_once 'util.php';

function getproblist()
{
  global $problist, $probcount;

  $res = mysql_query('SELECT * FROM prob_info WHERE avail="Y" ORDER BY prob_order');
  $probcount = mysql_num_rows($res);
  for($i = 0; $i<$probcount; $i++) {
    $problist[$i]['prob_id'] = mysql_result($res,$i,'prob_id');
    $problist[$i]['name'] = mysql_result($res,$i,'name');
  }
}

function makeproboptions()
{
  global $problist, $probcount;

  $poption = "<option value=\"0\">เลือกโจทย์ที่จะส่ง</option>";
  for($i=0; $i<$probcount; $i++)
    $poption = $poption . 
               "<option value=\"" . $problist[$i]['prob_id'] . "\">" .
               $problist[$i]['name'] . "</option>";
  return $poption;
}

function displaysubinfo($id, $prob_id, $sub_num)
{
  $res = mysql_query("SELECT time, CHAR_LENGTH(code) AS len FROM submission WHERE user_id=\"$id\" " .
                     "AND prob_id=\"$prob_id\" AND sub_num=\"$sub_num\"");
  $subtime = mysql_result($res,0,'time');
  $sublen = mysql_result($res,0,'len');
  $q = "SELECT res_text, grading_msg FROM grd_status, res_desc " .
       "WHERE grd_status.user_id=\"$id\" " . 
       "AND grd_status.prob_id=\"$prob_id\" " .
       "AND grd_status.res_id=res_desc.res_id";
  $res = mysql_query($q);
  $result_text = mysql_result($res,0,'res_text');

  echo '<div class="tools-alert tools-alert-blue">';
  //echo '<dd>';
  
  echo '<h4>';
  echo 'ส่งตรวจ : <span class="badge badge-blue">'.$sub_num.'</span> ครั้ง';
  echo '</h4>';
  
  echo '<h4>';
  echo 'ส่งล่าสุด : '.$subtime.' ขนาด '.$sublen.' ไบต์.';
  echo '</h4>'; 
  //echo '</dd>';
  
  //echo '<dd>';
  echo '<h4>';
  echo "ผลการตรวจ : ".$result_text.' <tt>'.mysql_result($res,0,'grading_msg').'</tt>';
  echo '</h4>';
  //echo '</dd>';
  
  //echo '<dd>';

  // compiler message
  if(defined('SHOW_COMPILER_MSG')) {
    $url = "viewmsg.php?id=$id&pid=$prob_id";
    echo "<a href='$url' target='_blank' class='btn btn-red btn-small'>compiler message</a>";
  }

  // links to source file
  if(defined('SOURCE_DOWNLOAD')) {
    $url = "viewcode.php?id=$id&pid=$prob_id&num=$sub_num";
    echo " <a href='$url' target='_blank' class='btn btn-green btn-small'>ไฟล์</a>";
  }

  // analysis mode
  if(defined('ANALYSIS_MODE')) {
    echo "<FONT SIZE=-1>";
    echo "<a href=\"viewoutput.php?id=$id&pid=$prob_id&num=$sub_num\">[outputs]</a>";
    echo "</FONT>";
  }
  //echo '</dd><br />';
  echo "</div>";
}

function displayprobinfo($id, $prob_id)
{
  //query for recent submission
  mysql_query("LOCK TABLES submission READ, grd_status READ, res_desc READ");
  $q = "SELECT MAX(sub_num) AS sub_num FROM submission WHERE user_id=\"$id\" " .
       "AND prob_id=\"$prob_id\"";
  $res = mysql_query($q);
  if((mysql_num_rows($res)==1) && (mysql_result($res,0,'sub_num')!=NULL)) {
    $maxsub_num = mysql_result($res,0,'sub_num');
    displaysubinfo($id, $prob_id, $maxsub_num);
  } else
    echo '<div class="tools-alert tools-alert-red"><h4>ยังไม่ได้ส่ง</h4></div>';
  mysql_query("UNLOCK TABLES");
}

function listprob($id)
{
  global $problist, $probcount;

  for($i=1; $i<=$probcount; $i++) {
    $name = $problist[$i-1]['name'];
    $prob_id = $problist[$i-1]['prob_id'];

    //echo '<div class="tools-alert tools-alert-blue">';
    //echo '<span style="font-size: 14pt; font-weight: bold;">ปัญหา#'.$i.' : '.$name." [" . $prob_id . "]</span></div>";
    
    $txt = '<span style="font-size: 14pt; font-weight: bold;">ปัญหา#'.$i.' : '.$name.' ['.$prob_id.']</span>';
    echo "<fieldset><legend>$txt</legend>";
    displayprobinfo($id, $prob_id);
    echo '</fieldset>';
  }

}

function displaymessage()
{
  if(!empty($_SESSION['msg'])) {
    echo "<b>" . $_SESSION['msg'] . "</b>";
    echo "<hr>";
    unset($_SESSION['msg']);
  }
}

function getteamlist($user_group)
{
  global $teamcount, $teamlist;

  $res = mysql_query("SELECT * FROM user_info " .
                     "WHERE grp=\"$user_group\" AND " .
                     "type='" . USERTYPE_CONTESTANT . "'");
  $teamcount = mysql_num_rows($res);
  for($i=0; $i<$teamcount; $i++) {
    $teamlist[$i]['user_id'] = mysql_result($res,$i,'user_id');
    $teamlist[$i]['name'] = mysql_result($res,$i,'name');
  }
}

function listteam($user_group)
{
  global $teamcount, $teamlist;

  for($i=0; $i<$teamcount; $i++) {
    echo "<font size=+2><b>";
    echo $teamlist[$i]['user_id']. " : ";
    echo $teamlist[$i]['name'];
    echo "</b></font><br>\n";
    listprob($teamlist[$i]['user_id']);
  }
}

function listadmintools()
{
  echo '<a href="admin/upload_std_info.php">[upload student info]</a> ';
  echo '<a href="admin/list_password.php">[list user passwords]</a> ';
  echo '<a href="admin/random_user_password.php">[random user passwords]</a> ';
  echo '<a href="admin/list_printer.php">[list printers]</a> ';
  echo "<hr>\n";
}

function displaysubmitbox($id, $proboption)
{
  echo <<<SUBMIT

<form action="submit.php" method="post" enctype="multipart/form-data" class="forms">
              <fieldset>
                  <legend><span style="font-size: 14pt; font-weight: bold;">ส่งโจทย์</span></legend>
                <label>
                    <input type="hidden" name="id" value="$id">
                    <select name="probid" style="width: 100%; height: 30px; font-size: 14pt;">
                        $proboption
                    </select>
                </label>
                <label>
                <input type="file" name="code">
                </label>
                <p>
                  <button class="btn btn-blue" type="submit">ส่งไปตรวจ</button>
                </p>
              </fieldset>
            </form>
SUBMIT;
}

function displayprintingbox()
{
  echo <<<PRINTING
<b>Printing</b>

  <form action="print.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?php echo $id; ?>">
  <input type="file" name="code" size="20">
  <input type="submit" name="print" value="print">
  </form>
<hr>
PRINTING;
}

checkauthen();
$id = $_SESSION['id'];
connect_db();
getproblist();
if($_SESSION['type']==USERTYPE_SUPERVISOR)
  getteamlist($_SESSION['group']);
$proboption = makeproboptions();
?>