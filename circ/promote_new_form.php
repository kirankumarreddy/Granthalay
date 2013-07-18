<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  session_cache_limiter(null);

  $tab = "circulation";
  $restrictToMbrAuth = TRUE;
  $nav = "promote";
  $cancelLocation = "../circ/index.php";  
  require_once("../functions/inputFuncs.php");
  require_once("../shared/logincheck.php");
  require_once("../shared/get_form_vars.php");
  require_once("../shared/header.php");
  require_once("../classes/School.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);
  $headerWording = $loc->getText("sclFldsPromote");
  $scl = new School();
  if (isset($_GET["sclid"])){
  	$sclid = $_GET["sclid"];
  	$_GET["sclid"]=$sclid;
  } else {
  	require("../shared/get_form_vars.php");
  	$sclid = $postVars["sclid"];
  }
  $cancelLocation = "../circ/promote_view.php?sclid=".$sclid."&reset=Y";
  
?>
<form name="newpromoteform" method="POST" action="../circ/promote_new.php">
<input type="hidden" name="sclid" value="<?php echo H($sclid);?>">
<?php include("../circ/promote_fields.php"); ?>
<?php include("../shared/footer.php"); ?>
