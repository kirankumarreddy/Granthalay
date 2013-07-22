<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  session_cache_limiter(null);

  $tab = "circulation";
  $restrictToMbrAuth = TRUE;
  $nav = "schoolEdit";
  require_once("../functions/inputFuncs.php");
  require_once("../shared/logincheck.php");

  require_once("../classes/School.php");
  require_once("../classes/SchoolQuery.php");

  if (isset($_GET["sclid"])){
    $sclid = $_GET["sclid"];
  } else {
    require("../shared/get_form_vars.php");
    $sclid = $postVars["sclid"];
  }
  $sclQ = new SchoolQuery();
  $sclQ->connect();
  $scl = $sclQ->get($sclid);
  $sclQ->close();
  require_once("../shared/header.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);
  $headerWording = $loc->getText("sclEditForm");

  $cancelLocation = "../circ/scl_view.php?sclid=".$sclid."&reset=Y";
?>

<form name="editSclform" method="POST" action="../circ/scl_edit.php">
<input type="hidden" name="sclid" value="<?php echo H($sclid);?>">
<?php include("../circ/scl_fields.php"); ?>
<?php include("../shared/footer.php"); ?>
