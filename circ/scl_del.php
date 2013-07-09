<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  $tab = "circulation";
  $restrictToMbrAuth = TRUE;
  $nav = "deletedone";
  $restrictInDemo = true;
  require_once("../shared/logincheck.php");
  require_once("../classes/SchoolQuery.php");
  require_once("../classes/School.php");
  require_once("../functions/errorFuncs.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);

  $sclid = $_GET["sclid"];
  $sclName = $_GET["name"];

  #**************************************************************************
  #*  Delete library member
  #**************************************************************************
  $sclQ = new SchoolQuery();
  $sclQ->connect();
  $sclQ->delete($sclid);
  $sclQ->close();

  #**************************************************************************
  #*  Show success page
  #**************************************************************************
  require_once("../shared/header.php");
  echo $loc->getText("sclDelSuccess",array("name"=>$sclName));
  
?>
<br><br>
<a href="../circ/scl_index.php"><?php echo $loc->getText("sclDelReturn");?></a>
<?php require_once("../shared/footer.php"); ?>
