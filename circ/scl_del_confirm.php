<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  $tab = "circulation";
  $restrictToMbrAuth = TRUE;
  $nav = "schoolDelete";
  require_once("../shared/logincheck.php");
  require_once("../classes/School.php");
  require_once("../classes/SchoolQuery.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);

  $sclid = $_GET["sclid"];

  #****************************************************************************
  #*  Getting school name
  #****************************************************************************
  $sclQ = new SchoolQuery();
  $sclQ->connect();
  $scl = $sclQ->get($sclid);
  $sclQ->close();
  $sclName = $scl->getSchoolName();

  #****************************************************************************
  #*  Getting checkout count
  #****************************************************************************
  $sclQ = new SchoolQuery();
  $sclQ->connect();
  $deleteConfirm = $sclQ->deleteConfirm($sclid);
  $sclQ->close();
  
  #**************************************************************************
  #*  Show confirm page
  #**************************************************************************
  require_once("../shared/header.php");

  if (!$deleteConfirm) {
?>
<center>
  <?php echo $loc->getText("sclDelConfirmWarn",array("name"=>$sclName));?>
  <br><br>
  <a href="../circ/scl_view.php?sclid=<?php echo HURL($sclid);?>&amp;reset=Y"><?php echo $loc->getText("sclDelConfirmReturn"); ?></a>
</center>

<?php
  } else {
?>
<center>
<form name="delschoolform" method="POST" action="../circ/scl_view.php?sclid=<?php echo HURL($sclid);?>&amp;reset=Y">
<?php echo $loc->getText("sclDelConfirmMsg",array("name"=>$sclName)); ?>
<br><br>
      <input type="button" onClick="self.location='../circ/scl_del.php?sclid=<?php echo H(addslashes(U($sclid)));?>&amp;name=<?php echo H(addslashes(U($sclName)));?>'" value="<?php echo $loc->getText("circDelete"); ?>" class="button">
      <input type="submit" value="<?php echo $loc->getText("circCancel"); ?>" class="button">
</form>
</center>
<?php 
  }
  include("../shared/footer.php");
?>
