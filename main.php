<?php include_once 'Functions.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>ระบบตรวจ Code [C/C++] - Comprotournament#5</title>
  
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" href="kube/css/kube.min.css" />
  <link rel="stylesheet" href="kube/mystyle.css" />

  <script src="kube/jquery.js"></script>
  <script src="kube/js/kube.min.js"></script>
</head>
<body>
<div class="units-row">
    <div class="unit-centered unit-80 bg-white">
      <div class="units-row end">
              <div class="unit-70 padding-20">
                <h1>ยินดีต้อนรับ : 
                  <span class="label label-blue label-outline">
                    <font size="6"><?php echo getname($id); ?></font>
                  </span>
                </h1>
              </div>
              <div class="unit-30 padding-20" align="right">
                <a class="btn btn-red btn-outline" href="login.php">ออกจากระบบ</a>
              </div>
          </div>
    </div> <br />


<?php if($_SESSION['type']==USERTYPE_ADMIN): ?>
<div class="unit-centered unit-80 bg-white padding-10">
  <?php listadmintools(); ?>
</div>
<?php endif; ?>

<div class="unit-centered unit-80">
    <?php displaymessage(); ?>
</div>

<?php if(($_SESSION['type']==USERTYPE_ADMIN) || ($_SESSION['type']==USERTYPE_CONTESTANT)) : ?>
  <?php if(!isset($_GET["url"])): ?>
  <div class="unit-centered unit-80">
    <div class="utits-row end">
      <div class="unit-60 padding-10 bg-white">
        <?php listprob($id); ?>
      </div>
      <div class="unit-40 padding-10 bg-white">
        <?php displaysubmitbox($id, $proboption); ?>
      </div>
    </div>
  </div>
  <?php else: ?>
  <div class="unit-centered unit-80">
    <div class="utits-row end">
      <div class="padding-10 bg-white">
        <?php require_once($_GET["url"]); ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

<?php else: ?>
  <?php listteam($_SESSION['group']); ?>
<?php endif; ?>
</div>

</body>
</html>

<?php close_db(); ?>
